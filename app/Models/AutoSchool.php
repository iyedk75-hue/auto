<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutoSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'whatsapp_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_ADMIN);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_CANDIDATE);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(ExamSchedule::class);
    }
}
