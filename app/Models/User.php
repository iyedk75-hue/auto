<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CANDIDATE = 'candidate';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'auto_school_id',
        'phone',
        'status',
        'balance_due',
        'registered_at',
        'device_uuid',
        'device_bound_at',
        'last_login_at',
        'last_login_ip',
        'last_user_agent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance_due' => 'decimal:2',
            'registered_at' => 'datetime',
            'device_bound_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isSchoolAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasLearningAccess(): bool
    {
        return $this->isAdmin() || ($this->status ?? 'inactive') === 'active';
    }

    public function autoSchool(): BelongsTo
    {
        return $this->belongsTo(AutoSchool::class);
    }

    public function quizSessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentRecord::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(ExamSchedule::class);
    }
}
