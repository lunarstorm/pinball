<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Models;

use Vio\Pinball\Models\BaseModel;

class FeedGroup extends BaseModel
{
    protected $guarded = [];

    public $timestamps = false;

    public function story()
    {
        return $this->belongsTo(FeedStory::class, 'story_id');
    }

    public static function remove($storyId, $name = 'default')
    {
        return static::query()
            ->where('story_id', $storyId)
            ->whereName($name)
            ->delete();
    }

    public static function assign($storyId, $value, $name = 'default')
    {
        return static::query()
            ->updateOrCreate([
                'story_id' => $storyId,
                'name' => $name,
            ], [
                'value' => $value
            ]);
    }
}
