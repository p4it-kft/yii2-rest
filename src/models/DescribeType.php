<?php

namespace p4it\rest\server\models;

use yii\data\DataFilter;

class DescribeType {

    public static function getType($type): ?string
    {
        switch ($type) {
            case DataFilter::TYPE_INTEGER:
                return 'integer';
            case DataFilter::TYPE_FLOAT:
                return 'number';
            case DataFilter::TYPE_BOOLEAN:
                return DataFilter::TYPE_BOOLEAN;
            case DataFilter::TYPE_ARRAY:
                return DataFilter::TYPE_ARRAY;
            case DataFilter::TYPE_DATETIME:
            case DataFilter::TYPE_DATE:
            case DataFilter::TYPE_TIME:
            default:
                return 'string';
        }
    }
}
