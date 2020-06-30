<?php

namespace p4it\rest\server\models\response;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class CreateResponse {
    public static function getResponses($modelClass) {
        return [
            new DescribeResponse([
                'statusCode' => 201,
                'description' => 'Create ' . $modelClass,
                'content' => new DescribeResource(['modelClass' => $modelClass])
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 422,
                'description' => 'Validation failed ' . Inflector::pluralize($modelClass),
                'content' => new DescribeResource(['modelClass' => $modelClass])
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 500,
                'description' => 'Failed to update the object for unknown reason ' . Inflector::pluralize($modelClass),
            ]),
        ];
    }
}