<?php

namespace Vio\Pinball\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            static::setDefaults($model);
        });
    }

    public static function setDefaults($model)
    {
        // To be overridden
    }

    public static function makeWithDefaults()
    {
        $model = new static();
        $columns = Schema::getColumnListing($model->getTable());
        $attributes = array_fill_keys($columns, null);
        $attributesToSet = array_merge($attributes, $model->attributesToArray());
        $model->setRawAttributes($attributesToSet);
        static::setDefaults($model);
        return $model;
    }

    public function autoFill($data)
    {
        $table = $this->getTable();
        $columns = Schema::getColumnListing($table);

        $data = collect($data)->reject(function ($value, $key) use ($columns) {
            return !in_array($key, $columns);
        })->toArray();

        return parent::forceFill($data);
    }
}
