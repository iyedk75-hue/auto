<?php

namespace App\Http\Controllers;

use App\Models\PaymentRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $statusFilter = in_array($status, PaymentRecord::statuses(), true) ? $status : null;

        return view('admin.payments.index', [
            'statusFilter' => $statusFilter,
            'payments' => PaymentRecord::query()
                ->when($statusFilter !== null, fn ($query) => $query->where('status', $statusFilter))
                ->with('user')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.payments.create', [
            'payment' => new PaymentRecord(),
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayment($request);

        PaymentRecord::create($validated);

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement ajouté avec succès.');
    }

    public function edit(PaymentRecord $payment): View
    {
        return view('admin.payments.edit', [
            'payment' => $payment,
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, PaymentRecord $payment): RedirectResponse
    {
        $validated = $this->validatePayment($request);

        $payment->update($validated);

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement mis à jour.');
    }

    public function destroy(PaymentRecord $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement supprimé.');
    }

    private function validatePayment(Request $request): array
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_CANDIDATE),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(PaymentRecord::statuses())],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $paidAt = $validated['paid_at'] ?? null;

        if ($validated['status'] === PaymentRecord::STATUS_PAID) {
            $validated['paid_at'] = $paidAt ?? now();
        } else {
            $validated['paid_at'] = null;
        }

        return $validated;
    }
}
