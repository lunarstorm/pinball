<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Facades;

use Vio\Pinball\Patterns\ActivityFeed\Models\FeedStory;

class Feed
{
    public function story($params = [])
    {
        FeedStory::make()
            ->about($user);

        return [];
    }
}
