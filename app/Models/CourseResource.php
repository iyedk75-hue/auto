<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseResource extends Model
{
    use HasFactory;

    /**
     * UUID primary key.
     */
    public $incrementing = false;

    protected $keyType = 'string';

    public const TYPE_VIDEO = 'video';

    public const TYPE_PDF = 'pdf';

    public const TYPE_NOTE = 'note';

    public const TYPES = [
        self::TYPE_VIDEO,
        self::TYPE_PDF,
        self::TYPE_NOTE,
    ];

    protected $fillable = [
        'id',
        'course_id',
        'resource_type',
        'title',
        'title_ar',
        'note_body',
        'note_body_ar',
        'file_path',
        'file_mime',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isVideo(): bool
    {
        return $this->resource_type === self::TYPE_VIDEO;
    }

    public function isPdf(): bool
    {
        return $this->resource_type === self::TYPE_PDF;
    }

    public function isNote(): bool
    {
        return $this->resource_type === self::TYPE_NOTE;
    }
}
