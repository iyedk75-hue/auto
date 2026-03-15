<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /**
     * UUID primary key.
     */
    public $incrementing = false;

    protected $keyType = 'string';

    public const PROTECTED_MEDIA_DIRECTORY = 'courses/protected/media';

    public const PROTECTED_PDF_DIRECTORY = 'courses/protected/pdf';

    public const CATEGORIES = [
        'priority_rules',
        'traffic_signs',
        'driving_safety',
        'vehicle_basics',
    ];

    protected $fillable = [
        'id',
        'category',
        'title',
        'title_ar',
        'description',
        'description_ar',
        'content',
        'content_ar',
        'cover_path',
        'duration_minutes',
        'media_path',
        'media_mime',
        'pdf_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return array<string, string>
     */
    public static function categoryLabels(): array
    {
        return [
            'priority_rules' => __('ui.classroom.categories.priority_rules'),
            'traffic_signs' => __('ui.classroom.categories.traffic_signs'),
            'driving_safety' => __('ui.classroom.categories.driving_safety'),
            'vehicle_basics' => __('ui.classroom.categories.vehicle_basics'),
        ];
    }

    public function titleForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return $this->title_ar;
        }

        return $this->title;
    }

    public function descriptionForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return $this->description_ar;
        }

        return $this->description;
    }

    public function contentForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return $this->content_ar;
        }

        return $this->content;
    }

    public function hasArabicTranslation(): bool
    {
        return filled($this->title_ar)
            || filled($this->description_ar)
            || filled($this->content_ar);
    }

    public function mediaDisk(): ?string
    {
        return $this->assetDisk($this->media_path);
    }

    public function pdfDisk(): ?string
    {
        return $this->assetDisk($this->pdf_path);
    }

    public function mediaUrl(): ?string
    {
        if (! $this->media_path) {
            return null;
        }

        return route('courses.media', $this);
    }

    public function pdfUrl(): ?string
    {
        if (! $this->pdf_path) {
            return null;
        }

        return route('courses.pdf', $this);
    }

    public function deleteMediaAsset(): void
    {
        $this->deleteAsset($this->media_path);
    }

    public function deletePdfAsset(): void
    {
        $this->deleteAsset($this->pdf_path);
    }

    private function deleteAsset(?string $path): void
    {
        $disk = $this->assetDisk($path);

        if ($disk && $path) {
            Storage::disk($disk)->delete($path);
        }
    }

    private function assetDisk(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Storage::disk('local')->exists($path)) {
            return 'local';
        }

        if (Storage::disk('public')->exists($path)) {
            return 'public';
        }

        if (Str::startsWith($path, 'courses/protected/')) {
            return 'local';
        }

        return 'public';
    }
}
