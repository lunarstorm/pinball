<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Support\Arr;
use Vio\Pinball\Models\BaseModel;

trait PublishesToFeed
{
    /**
     * Whether the publishable link should open in a new window
     * when clicked on from the activty feed.
     *
     * @var boolean|null
     */
    public $linkOpensInNewWindow;

    
}
