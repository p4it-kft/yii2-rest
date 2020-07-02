<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class ViewResponses {
    public static function get($modelClass) {
        return [
            new DescribeResponse([
                'statusCode' => 200,
                'description' => 'View ' . $modelClass,
                'content' => new DescribeResource(['modelClass' => $modelClass])
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 404,
                'description' => 'Object not found ' . Inflector::pluralize($modelClass),
            ]),
        ];
    }
}