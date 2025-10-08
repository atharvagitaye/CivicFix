<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'department',
        'employee_id',
    ];

    /**
     * Get the user that owns the staff record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the issues assigned to this staff member through assignments.
     */
    public function assignedIssues(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Issue::class, IssueAssignment::class, 'staff_id', 'id', 'id', 'issue_id');
    }

    /**
     * Get the issue assignments for this staff member.
     */
    public function issueAssignments(): HasMany
    {
        return $this->hasMany(IssueAssignment::class, 'staff_id');
    }

    /**
     * Get the issue updates created by this staff member.
     */
    public function issueUpdates(): HasMany
    {
        return $this->hasMany(IssueUpdate::class, 'updated_by');
    }
}
