<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

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

    protected $fillable = [
        'id',
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

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}
