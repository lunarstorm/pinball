<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Contracts;

interface Publishable {

    /**
     * Returns an array representation of the publishable
     * object for activity feeds.
     *
     * @return array
     */
    public function toFeed();

    /**
     * Returns the descriptive label for this publishable
     * object.
     *
     * @return string
     */
    public function feedLabel();

    /**
     * The url link associated to this publishable
     * object, if applicable.
     *
     * @return string|null
     */
    public function feedLink();

}
