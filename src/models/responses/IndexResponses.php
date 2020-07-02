<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class IndexResponses {
    public static function get($modelClass, $searchModelClass) {
        return [
            new DescribeResponseIndex([
                'statusCode' => 200,
                'description' => 'Get ' . Inflector::pluralize($modelClass),
                'content' => new DescribeResource(['modelClass' => $modelClass])
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 422,
                'description' => 'Validation failed ' . Inflector::pluralize($modelClass),
                'content' => new DescribeResource(['modelClass' => $searchModelClass])
            ]),
        ];
    }
}