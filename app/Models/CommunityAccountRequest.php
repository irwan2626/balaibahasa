<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityAccountRequest extends Model
{
    protected $fillable = [
        'name',
        'community_name',
        'logo_path',
        'position',
        'vision',
        'mission',
        'vision_mission',
        'background',
        'phone',
        'email',
        'password',
        'status',
        'terms_accepted_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'terms_accepted_at' => 'datetime',
        ];
    }

    public function stories(): HasMany
    {
        return $this->hasMany(CommunityStory::class, 'community_account_request_id');
    }
}
