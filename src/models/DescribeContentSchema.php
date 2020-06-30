<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeContentSchema extends Model
{
    public $type = 'object';
    public $properties = [];

    public function attributes()
    {
        return [
            'type',
            'properties'
        ];
    }

    public function addProperty($attribute, $description, $type = null)
    {
        $this->properties[$attribute] = [
            'description' => $description,
            'type' => $type
        ];

        return $this;
    }


}