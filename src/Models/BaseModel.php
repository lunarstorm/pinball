<?php

namespace Vio\Pinball\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{

    public static function boot()
    {
        parent::boot();

        static::saving(function($model){
            static::setDefaults($model);
        });
    }

    public static function setDefaults($model)
    {
        // To be overridden
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
