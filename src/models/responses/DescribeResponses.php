<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResponse;
use yii\base\Controller;

class DescribeResponses
{
    public static function get(Controller $controller)
    {
        return [
            new DescribeResponse([
                'statusCode' => 200,
                'description' => 'Describe '.$controller->id,
            ]),
        ];
    }
}