<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
