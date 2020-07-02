<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeResponseValidationFailed;
use p4it\rest\server\resources\ValueResource;
use yii\helpers\Inflector;

class ValuesResponses {
    public static function get($modelClass) {
        return [
            new DescribeResponse([
                'statusCode' => 200,
                'description' => 'Values ' . ValueResource::class,
                'content' => new DescribeResource(['modelClass' => ValueResource::class])
            ]),
        ];
    }
}