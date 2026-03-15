<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSchedule extends Model
{
    use HasFactory;

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
        return [self::STATUS_PLANNED, self::STATUS_PASSED, self::STATUS_FAILED, self::STATUS_POSTPONED];
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
