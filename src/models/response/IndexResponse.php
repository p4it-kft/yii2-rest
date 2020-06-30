<?php

namespace p4it\rest\server\models\response;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class IndexResponse {
    public static function getResponses($modelClass, $searchModelClass) {
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