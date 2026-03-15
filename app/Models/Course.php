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
        'description',
        'content',
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
}
