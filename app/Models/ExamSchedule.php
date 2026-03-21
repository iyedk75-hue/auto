<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSchedule extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PLANNED = 'planned';
    public const STATUS_PASSED = 'passed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_POSTPONED = 'postponed';

    protected $fillable = [
        'user_id',
        'auto_school_id',
        'exam_date',
        'status',
        'note',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PLANNED,
            self::STATUS_PASSED,
            self::STATUS_FAILED,
            self::STATUS_POSTPONED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PLANNED => 'Planifié',
            self::STATUS_PASSED => 'Réussi',
            self::STATUS_FAILED => 'Échoué',
            self::STATUS_POSTPONED => 'Reporté',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function autoSchool(): BelongsTo
    {
        return $this->belongsTo(AutoSchool::class);
    }
}
