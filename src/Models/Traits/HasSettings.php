<?php

namespace Vio\Pinball\Models\Traits;

use Vio\Pinball\Models\Setting;

trait HasSettings
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function settings()
    {
        return $this->morphMany(Setting::class, 'scope')
            ->withTimestamps();
    }

    /**
     * Get the number of settings for the model.
     *
     * @return int
     */
    public function getSettingsCountAttribute()
    {
        return $this->settings->count();
    }
}
