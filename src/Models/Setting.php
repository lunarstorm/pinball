<?php

namespace Vio\Pinball\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'scope_type',
        'scope_id',
        'key',
        'value'
    ];

    protected $casts = [];

    protected $appends = [
        'value'
    ];

    /**
     * Get the model scope.
     */
    public function scope()
    {
        return $this->morphTo();
    }

    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value);
    }

    public static function getQuery($key, Model $scope = null)
    {
        $value = null;

        $scopeType = $scope->getMorphClass();

        $qry = static::query()
            ->where('key', $key);

        if (is_null($scope)) {
            $qry->whereNull('scope_type');
            $qry->whereNull('scope_id');
        } else {
            $qry->where('scope_type', $scopeType)
                ->where('scope_id', $scope->id);
        }

        return $qry;
    }

    public static function get($key, Model $scope = null)
    {
        $setting = static::getQuery($key, $scope)
            ->first();

        if (!$setting) {
            return null;
        }

        return $setting->value;
    }

    public static function set($key, $value, Model $scope = null)
    {
        $scopeType = null;
        $scopeId = null;

        if ($scope) {
            $scopeType = $scope->getMorphClass();
            $scopeId = $scope->id;
        }

        $setting = static::updateOrCreate([
            'scope_type' => $scopeType,
            'scope_id' => $scopeId,
            'key' => $key,
        ], [
            'value' => $value
        ]);

        return $setting->value;
    }

    public static function forget($key, Model $scope = null)
    {
        return static::getQuery($key, $scope)->delete();
    }

    public static function for($scope)
    {
        // todo: make this work, a scopable, fluent query
    }
}
