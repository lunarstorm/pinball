<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Support;

use Vio\Pinball\Patterns\ActivityFeed\Contracts\Publishable;
use Vio\Pinball\Patterns\ActivityFeed\Models\FeedStory;

/**
 * Story::make()->actor($user)->action('created', $project);
 * Story::make()->headline($actor, 'created', $project);
 */
class StoryBuilder
{
    public $story = null;

    public function __construct(array $attributes = [])
    {
        $this->story = new FeedStory($attributes);
    }

    public static function make(array $attributes = [])
    {
        return new self($attributes);
    }

    public function actor(Publishable $model)
    {
        $this->story->actor()->associate($model);

        return $this;
    }

    public function verb(string $verb)
    {
        $this->story->verb = $verb;

        return $this;
    }

    public function object(Publishable $model)
    {
        $this->story->objectModel()->associate($model);

        return $this;
    }

    public function target(Publishable $model)
    {
        $this->story->targetModel()->associate($model);

        return $this;
    }

    public function headline(Publishable $actor, string $verb, Publishable $object, Publishable $target = null)
    {
        $this->actor($actor)->verb($verb)->object($object);

        if ($target) {
            $this->target($target);
        }

        return $this;
    }
}
