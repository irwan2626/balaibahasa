<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class CommunityStory extends Model
{
    protected $fillable = [
        'community_account_request_id',
        'author_name',
        'author_email',
        'title',
        'story',
        'photo_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_comment',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CommunityAccountRequest::class, 'community_account_request_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CommunityStoryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getCoverPhotoPathAttribute(): ?string
    {
        $photo = $this->relationLoaded('photos')
            ? $this->photos->first()
            : $this->photos()->first();

        return $photo?->photo_path ?: $this->photo_path;
    }
}
