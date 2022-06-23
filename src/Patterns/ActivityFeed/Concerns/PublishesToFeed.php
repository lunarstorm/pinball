<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Concerns;

trait PublishesToFeed
{
    /**
     * Whether the publishable link should open in a new window
     * when clicked on from the activty feed.
     *
     * @var bool|null
     */
    public $linkOpensInNewWindow;
}
