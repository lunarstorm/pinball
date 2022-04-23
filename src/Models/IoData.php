<?php

namespace Vio\Pinball\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use io\data\EntityKeyValueModel;
use Vio\Pinball\Legacy\io\models\EntityKeyValueModel as ModelsEntityKeyValueModel;

class IoData extends ModelsEntityKeyValueModel
{
    use HasFactory, HasTimestamps;

    protected $table = 'io_data';

    protected $casts = [
        'value' => 'array'
    ];
}
