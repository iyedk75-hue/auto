<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    private const MANAGED_IMAGE_DIRECTORY = 'questions/images/';

    public $incrementing = false;
    protected $keyType = 'string';

    public const CATEGORIES = [
        'priorite',
        'signalisation',
        'vitesse',
        'stationnement',
        'conducteur_et_vehicule',
        'croisement_depassement',
    ];

    public const DIFFICULTIES = ['easy', 'medium', 'hard'];

    public static function categoryLabels(): array
    {
        return [
            'priorite' => 'Priorite',
            'signalisation' => 'Signalisation',
            'vitesse' => 'Vitesse',
            'stationnement' => 'Stationnement',
            'conducteur_et_vehicule' => 'Conducteur et vehicule',
            'croisement_depassement' => 'Croisement et depassement',
        ];
    }

    protected $fillable = [
        'id',
        'auto_school_id',
        'category',
        'image_url',
        'question_text',
        'correct_answer',
        'explanation',
        'difficulty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (! $value) {
            return $value;
        }

        $managedPath = $this->managedImagePathFromValue($value);

        if (! $managedPath) {
            return $value;
        }

        return '/storage/'.$managedPath;
    }

    public function externalImageUrl(): ?string
    {
        $value = $this->getRawOriginal('image_url');

        return $this->managedImagePathFromValue($value) ? null : $value;
    }

    public static function managedImagePathFromValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, self::MANAGED_IMAGE_DIRECTORY)) {
            return $value;
        }

        if (Str::startsWith($value, 'storage/'.self::MANAGED_IMAGE_DIRECTORY)) {
            return Str::after($value, 'storage/');
        }

        if (preg_match('#/storage/(questions/images/[^\?]+)#', $value, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    public function autoSchool(): BelongsTo
    {
        return $this->belongsTo(AutoSchool::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}
