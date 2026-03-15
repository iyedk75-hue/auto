<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * UUID primary key.
     */
    public $incrementing = false;

    protected $keyType = 'string';

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
}
