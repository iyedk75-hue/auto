<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\PaymentRecord;
use App\Models\User;
use App\Notifications\PaymentValidatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPaymentController extends Controller
{
    use InteractsWithAdminScope;

    public function index(Request $request): View
    {
        $admin = $this->adminUser($request);
        $status = $request->string('status')->toString();
        $statusFilter = in_array($status, PaymentRecord::statuses(), true) ? $status : null;
        $reviewFilter = $request->string('review')->toString();
        $pendingProofReview = $reviewFilter === 'proof-pending';
        $selectedSchoolInput = $request->query('auto_school_id');
        $selectedSchoolId = is_numeric($selectedSchoolInput) ? (int) $selectedSchoolInput : null;
        $availableSchools = $this->availableSchoolsForAdmin($admin);

        if ($selectedSchoolId && ! $availableSchools->contains('id', $selectedSchoolId)) {
            $selectedSchoolId = null;
        }

        return view('admin.payments.index', [
            'statusFilter' => $statusFilter,
            'pendingProofReview' => $pendingProofReview,
            'schools' => $availableSchools,
            'selectedSchoolId' => $selectedSchoolId,
            'payments' => $this->paymentQueryForAdmin($admin)
                ->when($selectedSchoolId !== null, fn ($query) => $query->whereHas('user', fn ($inner) => $inner->where('auto_school_id', $selectedSchoolId)))
                ->when($statusFilter !== null, fn ($query) => $query->where('status', $statusFilter))
                ->when($pendingProofReview, fn ($query) => $query
                    ->where('payment_method', PaymentRecord::METHOD_BANK_TRANSFER)
                    ->where('status', PaymentRecord::STATUS_PENDING)
                    ->whereNotNull('proof_path'))
                ->with(['user.autoSchool', 'reviewedBy'])
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        $admin = $this->adminUser($request);

        return view('admin.payments.create', [
            'payment' => new PaymentRecord(),
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $admin = $this->adminUser($request);
        $validated = $this->validatePayment($request, $admin);

        $payment = PaymentRecord::create($validated);
        $candidate = $payment->user()->firstOrFail();

        $this->syncCandidateAccess($candidate);
        $this->notifyCandidateIfPaymentValidated($payment, $candidate);

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement ajouté avec succès.');
    }

    public function edit(Request $request, PaymentRecord $payment): View
    {
        $admin = $this->adminUser($request);
        $this->ensureManagedPayment($admin, $payment);

        return view('admin.payments.edit', [
            'payment' => $payment,
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, PaymentRecord $payment): RedirectResponse
    {
        $admin = $this->adminUser($request);
        $this->ensureManagedPayment($admin, $payment);
        $validated = $this->validatePayment($request, $admin);
        $previousStatus = $payment->status;
        $previousUserId = $payment->user_id;

        $payment->update($validated);
        $candidate = $payment->user()->firstOrFail();

        $this->syncCandidateAccess($candidate);

        if ($previousUserId !== $candidate->id) {
            $previousCandidate = User::query()->find($previousUserId);

            if ($previousCandidate) {
                $this->syncCandidateAccess($previousCandidate);
            }
        }

        $this->notifyCandidateIfPaymentValidated($payment, $candidate, $previousStatus);

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement mis à jour.');
    }

    public function destroy(Request $request, PaymentRecord $payment): RedirectResponse
    {
        $this->ensureManagedPayment($this->adminUser($request), $payment);

        $candidate = $payment->user()->first();
        $payment->deleteProofAsset();

        $payment->delete();

        if ($candidate) {
            $this->syncCandidateAccess($candidate);
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Paiement supprimé.');
    }

    public function proof(Request $request, PaymentRecord $payment): BinaryFileResponse
    {
        $this->ensureManagedPayment($this->adminUser($request), $payment);
        abort_unless($payment->hasProof() && $payment->proofDisk(), 404);

        $path = Storage::disk('local')->path($payment->proof_path);

        return response()->file($path, [
            'Content-Type' => $payment->proof_mime ?: (Storage::disk('local')->mimeType($payment->proof_path) ?: 'application/octet-stream'),
            'Content-Disposition' => 'inline; filename="'.basename($payment->proof_path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    private function validatePayment(Request $request, User $admin): array
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_CANDIDATE),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', Rule::in(PaymentRecord::methods())],
            'transfer_reference' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(PaymentRecord::statuses())],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->candidateQueryForAdmin($admin)->whereKey($validated['user_id'])->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'Ce candidat n’est pas accessible depuis votre espace admin.',
            ]);
        }

        $paidAt = $validated['paid_at'] ?? null;

        if ($validated['status'] === PaymentRecord::STATUS_PAID) {
            $validated['paid_at'] = $paidAt ?? now();
            $validated['reviewed_by_user_id'] = $admin->id;
            $validated['reviewed_at'] = now();
        } else {
            $validated['paid_at'] = null;
            $validated['reviewed_by_user_id'] = $admin->id;
            $validated['reviewed_at'] = now();
        }

        return $validated;
    }

    private function syncCandidateAccess(User $candidate): void
    {
        $hasPaidAccess = $candidate->payments()
            ->where('status', PaymentRecord::STATUS_PAID)
            ->exists();

        $candidate->forceFill([
            'status' => $hasPaidAccess ? 'active' : 'inactive',
        ])->save();
    }

    private function notifyCandidateIfPaymentValidated(PaymentRecord $payment, User $candidate, ?string $previousStatus = null): void
    {
        if ($payment->status !== PaymentRecord::STATUS_PAID || $previousStatus === PaymentRecord::STATUS_PAID) {
            return;
        }

        $candidate->notify(new PaymentValidatedNotification($payment));
    }
}
