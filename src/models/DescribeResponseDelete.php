<?php

namespace p4it\rest\server\models;

use modules\core\common\components\ActiveDataFilter;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeResponseDelete extends Model
{

    public $description;

    /**
     * 1XX, 2XX, 3XX, 4XX, and 5XX
     * @var integer
     */
    public $statusCode;

    public function attributes()
    {
        return [
            'description',
        ];
    }
}