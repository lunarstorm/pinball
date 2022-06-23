<?php

namespace Vio\Pinball\Patterns\ActivityFeed;

use Illuminate\Support\Str;
use ReflectionClass;

abstract class Story
{
    protected $actor = null;

    protected $object = null;

    protected $target = null;

    protected $verb = null;

    public function __construct()
    {
        if (! $this->verb) {
            $class = get_called_class();
            $reflection = new ReflectionClass($class);
            $verb = Str::studly($reflection->getShortName());
            $this->verb = $verb;
        }
    }

    public function verb(string $verb)
    {
        $this->verb = $verb;

        return $this;
    }

    public function metadata()
    {
        return [];
    }

    public static function makeFrom($params = [])
    {
        $story = new static();
        $story->verb = data_get($params, 'verb');
        $story->actor = data_get($params, 'actor');
        $story->object = data_get($params, 'object');
        $story->target = data_get($params, 'target');

        return $story;
    }

    abstract public function headline();
}
