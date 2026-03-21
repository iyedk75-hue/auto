<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseResource extends Model
{
    use HasFactory;

    /**
     * UUID primary key.
     */
    public $incrementing = false;

    protected $keyType = 'string';

    public const TYPE_AUDIO = 'audio';

    public const TYPE_NOTE = 'note';

    public const TYPES = [
        self::TYPE_AUDIO,
        self::TYPE_NOTE,
    ];

    public const PROTECTED_AUDIO_DIRECTORY = 'courses/protected/resources/audio';

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isAudio(): bool
    {
        return $this->resource_type === self::TYPE_AUDIO;
    }

    public function isNote(): bool
    {
        return $this->resource_type === self::TYPE_NOTE;
    }

    public function isFileResource(): bool
    {
        return $this->isAudio();
    }

    public function titleForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return $this->title_ar ?: $this->title;
        }

        return $this->title;
    }

    public function noteBodyForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return $this->note_body_ar ?: $this->note_body;
        }

        return $this->note_body;
    }

    public function hasArabicTranslation(): bool
    {
        return filled($this->title_ar) || filled($this->note_body_ar);
    }

    public function assetDisk(): ?string
    {
        if (! $this->isFileResource() || blank($this->file_path)) {
            return null;
        }

        if (Storage::disk('local')->exists($this->file_path)) {
            return 'local';
        }

        if (Storage::disk('public')->exists($this->file_path)) {
            return 'public';
        }

        return null;
    }

    public function deleteFileAsset(): void
    {
        $disk = $this->assetDisk();

        if ($disk && $this->file_path) {
            Storage::disk($disk)->delete($this->file_path);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toResolvedArray(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return [
            'key' => $this->getAttribute('legacy_key') ?: $this->id,
            'origin' => $this->getAttribute('legacy_key') ? 'legacy' : 'resource',
            'type' => $this->resource_type,
            'sort_order' => (int) ($this->sort_order ?? 0),
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'display_title' => $this->titleForLocale($locale),
            'note_body' => $this->note_body,
            'note_body_ar' => $this->note_body_ar,
            'display_note_body' => $this->noteBodyForLocale($locale),
            'file_path' => $this->file_path,
            'file_mime' => $this->file_mime,
            'created_at' => $this->created_at,
            'is_active' => (bool) ($this->is_active ?? true),
            'is_file' => $this->isFileResource(),
            'is_note' => $this->isNote(),
            'has_arabic_translation' => $this->hasArabicTranslation(),
        ];
    }
}
