<?php

namespace p4it\rest\server\models\response;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseDelete;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class DeleteResponse {
    public static function getResponses($modelClass) {
        return [
            new DescribeResponseDelete([
                'statusCode' => 204,
                'description' => 'Delete ' . $modelClass
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 500,
                'description' => 'Failed to update the object for unknown reason ' . Inflector::pluralize($modelClass),
            ]),
            new DescribeResponseValidationFailed([
                'statusCode' => 404,
                'description' => 'Object not found ' . Inflector::pluralize($modelClass),
            ]),
        ];
    }
}