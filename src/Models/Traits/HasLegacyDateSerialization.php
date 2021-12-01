<?php

namespace Vio\Pinball\Models\Traits;

use DateTimeInterface;

trait HasLegacyDateSerialization
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
