<?php

namespace Vio\Pinball\Patterns\ActivityFeed;

abstract class Verb
{
    public $actor = null;

    public $object = null;

    public $target = null;

    abstract public function headline();

    public function metadata()
    {
        return [];
    }
}
