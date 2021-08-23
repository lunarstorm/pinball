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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

        $exclude = ['id', 'created_at', 'updated_at', 'deleted_at'];

        $data = collect($data)->reject(function ($value, $key) use ($columns, $exclude) {
            return !in_array($key, $columns) || in_array($key, $exclude);
        })->toArray();

        return parent::forceFill($data);
    }
}
