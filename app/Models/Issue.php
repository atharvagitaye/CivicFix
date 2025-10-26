<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'category_id',
        'sub_category_id',
        'status',
        'priority',
        'location',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who reported this issue.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this issue.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sub-category of this issue.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    /**
     * Get the assignment for this issue.
     */
    public function assignment(): HasOne
    {
        return $this->hasOne(IssueAssignment::class);
    }

    /**
     * Get the updates for this issue.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(IssueUpdate::class);
    }

    /**
     * Get the media files for this issue.
     */
    public function media(): HasMany
    {
        return $this->hasMany(IssueMedia::class);
    }

    /**
     * Get the priority levels.
     */
    public static function getPriorityLevels(): array
    {
        return ['low', 'medium', 'high', 'urgent'];
    }

    /**
     * Get the status levels.
     */
    public static function getStatusLevels(): array
    {
        return ['submitted', 'in_progress', 'resolved'];
    }

    /**
     * Scope a query to only include issues with a specific priority.
     */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include issues assigned to a specific staff member.
     */
    public function scopeAssignedTo($query, int $staffId)
    {
        return $query->whereHas('assignment', function($q) use ($staffId) {
            $q->where('staff_id', $staffId);
        });
    }

    /**
     * Scope a query to only include issues reported by a specific user.
     */
    public function scopeReportedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
