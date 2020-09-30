<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace p4it\rest\server;

use p4it\rest\server\data\ActiveDataFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataFilter;

class IndexAction extends \yii\rest\IndexAction
{
    /**
     * @var DataFilter[]
     */
    public $relationDataFilters = [];

    /**
     * {@inheritDoc}
     */
    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();

                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();
        if($this->dataFilter instanceof ActiveDataFilter && $this->dataFilter->splitFilter) {
            if (isset($filter['whereFilter']) && $filter['whereFilter']) {
                $query->andWhere($filter['whereFilter']);
            }
            if (isset($filter['havingFilter']) && $filter['havingFilter']) {
                $query->andHaving($filter['havingFilter']);
            }
        } elseif (!empty($filter)) {
            $query->andWhere($filter);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}
