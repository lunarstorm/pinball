<?php

namespace Vio\Pinball\Patterns\ActivityFeed\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Support\Arr;
use Vio\Pinball\Models\BaseModel;

class FeedStory extends BaseModel
{
    use HasTimestamps;

    const STATUS_DELETED = -99;
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_VOID = -1;

    // Deprecated stuff:
    const TYPE_STATUS = 'status';
    const TYPE_EVENT = 'event';
    const TYPE_POST = 'post';
    const TYPE_GENERIC = 'generic';
    const TYPE_ACTION = 'action';

    protected $hidden = ['meta'];

    protected $fillabe = [
        'status'
    ];

    protected $casts = [
        'date' => 'datetime',
        'meta' => 'array'
    ];

    public static function setDefaults($model)
    {
        if (!$model->status) {
            $model->status = self::STATUS_PUBLISHED;
        }

        if (!$model->type) {
            $model->type = self::TYPE_GENERIC;
        }

        if (!$model->date) {
            $model->date = now();
        }
    }

    public function object_model()
    {
        return $this->morphTo(null, 'object', 'object_id');
    }

    public function target_model()
    {
        return $this->morphTo(null, 'target', 'target_id');
    }

    public function target2_model()
    {
        return $this->morphTo(null, 'target2', 'target2_id');
    }

    public function parent()
    {
        return $this->belongsTo(FeedStory::class, 'parent_id');
    }

    public function actor()
    {
        return $this->morphTo('user', 'actor', 'actor_id');
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', static::STATUS_PUBLISHED);
    }

    public function assignObjectTokens($key, $pair = [])
    {
        $this->attributes[$key] = data_get($pair, 0, null);
        $this->attributes[$key . '_id'] = data_get($pair, 1, null);
        return;
    }

    public function setActorAttribute($value)
    {
        if (is_array($value)) {
            return $this->assignObjectTokens('actor', $value);
        }

        $this->attributes['actor'] = $value;
    }

    public function setObjectAttribute($value)
    {
        if (is_array($value)) {
            return $this->assignObjectTokens('object', $value);
        }

        $this->attributes['object'] = $value;
    }

    public function setTargetAttribute($value)
    {
        if (is_array($value)) {
            return $this->assignObjectTokens('target', $value);
        }

        $this->attributes['target'] = $value;
    }

    public function setTarget2Attribute($value)
    {
        if (is_array($value)) {
            return $this->assignObjectTokens('target2', $value);
        }

        $this->attributes['target'] = $value;
    }

    public static function publish($o = [])
    {
        $user = request()->user();

        $defaults = [
            'user_id' => optional($user)->id,
            'date' => now(),
            'verb' => 'post',
            'actor' => '',
            'object' => '',
            'objectType' => null,
            'target' => '',
            'target2' => '',
            'details' => '',
            'replace' => false,
        ];
        $o += $defaults;

        $story = new static();
        $story->autoFill($o);

        if ($val = data_get($o, 'actor')) {
            $story->actor = $val;
        }

        if ($val = data_get($o, 'object')) {
            $story->object = $val;
        }

        if ($val = data_get($o, 'target')) {
            $story->target = $val;
        }

        if ($val = data_get($o, 'target2')) {
            $story->target2 = $val;
        }

        if ($objectType = data_get($o, 'objectType')) {
            $story->autoFill([
                'object_type' => $objectType
            ]);
        }

        $meta = $story->meta ?: [];

        if ($details = data_get($o, 'details')) {
            data_set($meta, 'details', $details);
        }

        $story->meta = $meta;

        $story->save();

        if ($o['replace']) {
            $qry = static::query()
                ->where('id', '<>', $story->id)
                ->where('verb', $story->verb);

            if ($val = $story->getAttribute('actor')) {
                $qry->whereActor($val);
            }

            if ($val = $story->getAttribute('actor_id')) {
                $qry->whereActorId($val);
            }

            if ($val = $story->getAttribute('object')) {
                $qry->whereObject($val);
            }

            if ($val = $story->getAttribute('object_id')) {
                $qry->whereObjectId($val);
            }

            if ($val = $story->getAttribute('object_type')) {
                $qry->whereObjectType($val);
            }

            if ($val = $story->getAttribute('target')) {
                $qry->whereTarget($val);
            }

            if ($val = $story->getAttribute('target_id')) {
                $qry->whereTargetId($val);
            }

            if ($val = $story->getAttribute('target2')) {
                $qry->whereTarget2($val);
            }

            if ($val = $story->getAttribute('target2_id')) {
                $qry->whereTarget2Id($val);
            }

            $qry->whereNull('parent_id')
                ->whereRaw("date(date) = ?", $story->date->format('Y-m-d'))
                ->update([
                    'parent_id' => $story->id,
                ]);
        }

        return $story;
    }

    public function scopeContainsObject(Builder $query, $object, $object_id)
    {
        return $query->where(function ($query) use ($object, $object_id) {
            $query->orWhere(function ($query) use ($object, $object_id) {
                return $query->where('object', $object)
                    ->where('object_id', $object_id);
            });

            $query->orWhere(function ($query) use ($object, $object_id) {
                return $query->where('target', $object)
                    ->where('target_id', $object_id);
            });

            $query->orWhere(function ($query) use ($object, $object_id) {
                return $query->where('target2', $object)
                    ->where('target2_id', $object_id);
            });
        });
    }

    public static function voidAll($o = [])
    {
        $defaults = [
            'object' => '',
            'object_id' => '',
            'verb' => null,
        ];
        $o += $defaults;

        $obj = data_get($o, 'object');
        $oid = data_get($o, 'object_id');

        $qry = static::query()
            ->whereContains($obj, $oid);

        if ($verb = data_get($o, 'verb')) {
            $qry->whereVerb($verb);
        }

        return $qry->update([
            'status' => static::STATUS_VOID
        ]);
    }
}
