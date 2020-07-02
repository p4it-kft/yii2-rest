<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseDelete;
use yii\helpers\Inflector;

class DeleteResponses
{
    public static function get($modelClass)
    {
        return [
            new DescribeResponseDelete([
                'statusCode' => 204,
                'description' => 'Delete '.$modelClass,
            ]),
            new DescribeResponse([
                'statusCode' => 500,
                'description' => 'Failed to update the object for unknown reason '.Inflector::pluralize($modelClass),
            ]),
            new DescribeResponse([
                'statusCode' => 404,
                'description' => 'Object not found '.Inflector::pluralize($modelClass),
            ]),
        ];
    }
}