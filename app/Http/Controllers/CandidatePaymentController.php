<?php

namespace App\Http\Controllers;

use App\Models\PaymentRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidatePaymentController extends Controller
{
    public function index(Request $request): View
    {
        return view('candidate.payments', [
            'payments' => $request->user()->payments()->latest()->paginate(10),
            'bankDetails' => config('codex.bank'),
            'proofMaxKb' => (int) config('codex.payments.proof_max_size_kb', 5120),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transfer_reference' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
            'proof_file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:'.(int) config('codex.payments.proof_max_size_kb', 5120),
            ],
        ]);

        $proof = $request->file('proof_file');
        $proofPath = $proof->store(PaymentRecord::PROTECTED_PROOF_DIRECTORY, 'local');

        PaymentRecord::create([
            'user_id' => $request->user()->id,
            'amount' => $validated['amount'],
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => $validated['transfer_reference'],
            'proof_path' => $proofPath,
            'proof_mime' => $proof->getMimeType(),
            'proof_uploaded_at' => now(),
            'status' => PaymentRecord::STATUS_PENDING,
            'note' => $validated['note'] ?? null,
        ]);

        if (! $request->user()->payments()->where('status', PaymentRecord::STATUS_PAID)->exists()) {
            $request->user()->forceFill(['status' => 'inactive'])->save();
        }

        return redirect()
            ->route('payments.index')
            ->with('status', __('ui.payments.bank_transfer_submitted'));
    }
}
