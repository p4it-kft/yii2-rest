<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeParameter extends Model
{
    public $name;
    public $in;
    public $description;
    public $required;

    public $schema;

}