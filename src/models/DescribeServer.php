<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeServer extends Model {
    public $url;
    public $description;
}
