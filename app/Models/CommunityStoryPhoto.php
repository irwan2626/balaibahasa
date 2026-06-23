<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityStoryPhoto extends Model
{
    protected $fillable = [
        'community_story_id',
        'photo_path',
        'sort_order',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(CommunityStory::class, 'community_story_id');
    }
}
