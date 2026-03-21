<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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

    public const PROTECTED_AUDIO_DIRECTORY = 'courses/protected/audio';

    public const AUDIO_MAX_KB = 262144;

    public const CATEGORIES = [
        'priority_rules',
        'traffic_signs',
        'driving_safety',
        'vehicle_basics',
    ];

    protected $fillable = [
        'id',
        'auto_school_id',
        'category',
        'title',
        'title_ar',
        'description',
        'description_ar',
        'content',
        'content_ar',
        'cover_path',
        'duration_minutes',
        'audio_path',
        'audio_mime',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function autoSchool(): BelongsTo
    {
        return $this->belongsTo(AutoSchool::class);
    }

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

    public static function audioMaxSizeLabel(): string
    {
        return (string) (self::AUDIO_MAX_KB / 1024).' MB';
    }

    public function resources(): HasMany
    {
        return $this->hasMany(CourseResource::class)
            ->whereIn('resource_type', CourseResource::TYPES)
            ->orderBy('sort_order')
            ->orderBy('created_at');
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

    public function audioDisk(): ?string
    {
        return $this->assetDisk($this->audio_path);
    }

    public function audioUrl(): ?string
    {
        if (! $this->hasAudioMedia()) {
            return null;
        }

        return route('courses.audio', $this);
    }

    public function deleteAudioAsset(): void
    {
        $this->deleteAsset($this->audio_path);
    }

    public function hasPersistedResources(): bool
    {
        return $this->resources()->exists();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function resolvedResources(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();
        $resources = $this->resources()->where('is_active', true)->get();

        if ($resources->isNotEmpty()) {
            return $resources->map(fn (CourseResource $resource) => $resource->toResolvedArray($locale))->values();
        }

        return $this->legacyResolvedResources($locale);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function legacyResolvedResources(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();
        $legacyResources = collect();

        if ($this->hasAudioMedia()) {
            $legacyResources->push($this->makeLegacyResource(
                key: 'legacy-audio',
                type: CourseResource::TYPE_AUDIO,
                title: $this->title.' · '.__('ui.classroom.audio'),
                titleAr: filled($this->title_ar) ? $this->title_ar.' · '.__('ui.classroom.audio') : null,
                filePath: $this->audio_path,
                fileMime: $this->audio_mime,
                sortOrder: 1,
                locale: $locale,
            ));
        }

        return $legacyResources
            ->sortBy(['sort_order', 'created_at'])
            ->values();
    }

    public function hasAudioMedia(): bool
    {
        if (blank($this->audio_path)) {
            return false;
        }

        $mime = (string) $this->audio_mime;

        if ($mime !== '') {
            return Str::startsWith($mime, 'audio/');
        }

        return Str::endsWith(Str::lower($this->audio_path), ['.mp3', '.wav', '.ogg', '.m4a', '.aac']);
    }

    /**
     * @return array<string, mixed>
     */
    private function makeLegacyResource(
        string $key,
        string $type,
        string $title,
        ?string $titleAr,
        ?string $filePath,
        ?string $fileMime,
        int $sortOrder,
        ?string $locale = null,
    ): array {
        $resource = new CourseResource([
            'resource_type' => $type,
            'title' => $title,
            'title_ar' => $titleAr,
            'file_path' => $filePath,
            'file_mime' => $fileMime,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);

        $resource->created_at = $this->created_at;
        $resource->setAttribute('legacy_key', $key);

        return $resource->toResolvedArray($locale);
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
