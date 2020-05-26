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
class DescribeRequestBody extends Model
{
    use DescribeContentTrait;

    public $description;
    public $required;

    public function attributes()
    {
        return [
            'description',
            'required',
            'content',
        ];
    }
}