<?php

namespace p4it\rest\server\resources;

use yii\base\Model;

class ValueResource extends Model
{
    public $id;
    public $name;
    public $count;

    public function fields()
    {
        return [
            'id',
            'name',
            'count' => fn(ValueResource $model) => (int)$model->count
        ];
    }
}
