<?php

namespace p4it\rest\server\models\parameter;

use p4it\rest\server\models\DescribeParameter;
use p4it\rest\server\models\DescribeParameterFilter;
use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeSchema;

class IndexParameter {
    public static function getParameters($modelClass) {
        return [
            new DescribeParameterFilter([
                'in' => 'query',
                'name' => 'filter',
                'content' => new DescribeResource(['modelClass' => $modelClass])
            ]),
            new DescribeParameter([
                'in' => 'query',
                'name' => 'sort',
                'schema' => new DescribeSchema(['type' => 'string'])
            ]),
            new DescribeParameter([
                'in' => 'query',
                'name' => 'page',
                'schema' => new DescribeSchema(['type' => 'string'])
            ]),
            new DescribeParameter([
                'in' => 'query',
                'name' => 'per-page',
                'schema' => new DescribeSchema(['type' => 'string'])
            ]),
            new DescribeParameter([
                'in' => 'query',
                'name' => 'expand',
                'schema' => new DescribeSchema(['type' => 'string'])
            ]),
        ];
    }
}