<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    /**
     * Get the admin record associated with the user.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the staff record associated with the user.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get the issues reported by the user.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Get the issues assigned to this staff member.
     */
    public function assignedIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'assigned_to');
    }

    /**
     * Get the assignments for this staff member.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(IssueAssignment::class, 'staff_id');
    }

    /**
     * Get the password reset records for the user.
     */
    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->staff()->exists();
    }

    /**
     * Check if user is a regular user (not admin or staff).
     */
    public function isUser(): bool
    {
        return !$this->isAdmin() && !$this->isStaff();
    }
}
