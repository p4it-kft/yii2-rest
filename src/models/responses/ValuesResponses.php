<?php

namespace p4it\rest\server\models\responses;

use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponseIndex;
use p4it\rest\server\resources\ValueResource;

class ValuesResponses
{
    public static function get($modelClass)
    {
        return [
            new DescribeResponseIndex([
                'statusCode' => 200,
                'description' => 'Values '.ValueResource::class,
                'content' => new DescribeResource(['modelClass' => ValueResource::class]),
            ]),
        ];
    }
}