<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribePath extends Model {

    public $url;

    public function attributes()
    {
        return ['operations'];
    }

    /**
     * @var DescribeOperation[]
     */
    public $operations = [];

    /**
     * @param DescribeOperation $operation
     * @return DescribePath
     */
    public function addOperation($operation)
    {
        $this->operations[$operation->getMethod()] = $operation;
        return $this;
    }
}
