<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueMedia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'issue_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'issue_id',
        'media_type',
        'media_url',
    ];

    /**
     * Get the issue that owns the media.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the media types.
     */
    public static function getMediaTypes(): array
    {
        return ['photo', 'video', 'document'];
    }

    /**
     * Check if the media is a photo.
     */
    public function isPhoto(): bool
    {
        return $this->media_type === 'photo';
    }

    /**
     * Check if the media is a video.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Check if the media is a document.
     */
    public function isDocument(): bool
    {
        return $this->media_type === 'document';
    }
}
