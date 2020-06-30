<?php

namespace p4it\rest\server\models\response;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseDelete;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class ViewResponse {
    public static function getResponses($modelClass) {
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