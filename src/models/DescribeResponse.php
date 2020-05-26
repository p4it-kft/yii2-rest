<?php

namespace p4it\rest\server\models;

use p4it\rest\server\traits\DescribeContentTrait;
use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeResponse extends Model
{
    use DescribeContentTrait;

    /**
     * 1XX, 2XX, 3XX, 4XX, and 5XX
     * @var integer
     */
    public $statusCode;
    public $description;

    public function attributes()
    {
        return [
            'description',
            'content'
        ];
    }
}