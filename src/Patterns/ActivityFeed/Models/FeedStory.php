<?php

namespace Pinball\Patterns\ActivityFeed\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Vio\Pinball\Models\BaseModel;

class FeedStory extends BaseModel
{
    use HasTimestamps;

    const STATUS_DELETED = -99;
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $hidden = ['meta'];

    protected $casts = [
        'date' => 'datetime',
        'meta' => 'array'
    ];

    public function object_model()
    {
        return $this->morphTo(null, 'object', 'object_id');
    }

    public function target_model()
    {
        return $this->morphTo(null, 'target', 'target_id');
    }

    public function target2_model()
    {
        return $this->morphTo(null, 'target2', 'target2_id');
    }

    public function parent()
    {
        return $this->belongsTo(FeedStory::class, 'parent_id');
    }

    public function actor()
    {
        return $this->morphTo('user', 'actor', 'actor_id');
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', static::STATUS_PUBLISHED);
    }
}
