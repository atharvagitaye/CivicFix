<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordReset extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reset_token',
        'token_expiry',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'token_expiry' => 'datetime',
    ];

    /**
     * Get the user that owns the password reset record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token has expired.
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->token_expiry);
    }

    /**
     * Generate a new reset token.
     */
    public static function generateToken(): string
    {
        return Str::random(60);
    }

    /**
     * Create a new password reset record.
     */
    public static function createForUser(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'reset_token' => self::generateToken(),
            'token_expiry' => Carbon::now()->addHours(24), // Token expires in 24 hours
        ]);
    }

    /**
     * Find a valid token for a user.
     */
    public static function findValidToken(string $token): ?self
    {
        $reset = self::where('reset_token', $token)->first();
        
        if ($reset && !$reset->isExpired()) {
            return $reset;
        }
        
        return null;
    }
}
