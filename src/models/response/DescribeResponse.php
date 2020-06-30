<?php

namespace p4it\rest\server\models\response;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponseDelete;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use yii\helpers\Inflector;

class DescribeResponse {
    public static function getResponses($modelClass) {
        return [
            new p4it\rest\server\models\DescribeResponse([
                'statusCode' => 200,
                'description' => 'Describe ' . $controller->id,
            ]),
        ];
    }
}