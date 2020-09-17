<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace p4it\rest\server;

use yii\data\DataFilter;

class IndexAction extends \yii\rest\IndexAction
{
    /**
     * @var DataFilter[]
     */
    public $relationDataFilters = [];

}
