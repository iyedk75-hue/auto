<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PaymentRecord extends Model
{
    use HasFactory, SoftDeletes;

    public const METHOD_MANUAL = 'manual';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';

    public const PROTECTED_PROOF_DIRECTORY = 'payments/proofs';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'transfer_reference',
        'proof_path',
        'proof_mime',
        'proof_uploaded_at',
        'reviewed_by_user_id',
        'reviewed_at',
        'status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'proof_uploaded_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public static function methods(): array
    {
        return [self::METHOD_MANUAL, self::METHOD_BANK_TRANSFER];
    }

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_OVERDUE];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function hasProof(): bool
    {
        return filled($this->proof_path);
    }

    public function proofDisk(): ?string
    {
        if (blank($this->proof_path)) {
            return null;
        }

        return Storage::disk('local')->exists($this->proof_path) ? 'local' : null;
    }

    public function deleteProofAsset(): void
    {
        if ($this->proof_path && $this->proofDisk()) {
            Storage::disk('local')->delete($this->proof_path);
        }
    }
}
