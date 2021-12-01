<?php

namespace Vio\Pinball\Models;

use DateTimeInterface;
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

    public function hasColumn($column)
    {
        return Schema::hasColumn($this->getTable(), $column);
    }

    public static function setDefaults($model)
    {
        // To be overridden
    }

    public static function makeWithDefaults()
    {
        $model = new static();
        $columns = Schema::getColumnListing($model->getTable());
        $attributes = [];

        foreach ($columns as $column) {
            $attributes[$column] = null;
        }

        $attributesToSet = array_merge($attributes, $model->getAttributes());

        // Exclude id and auto timestamp columns
        $exclude = ['id', 'created_at', 'updated_at', 'deleted_at'];

        $attributesToSet = collect($attributesToSet)->reject(function ($value, $key) use ($exclude) {
            return in_array($key, $exclude);
        })->toArray();

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
