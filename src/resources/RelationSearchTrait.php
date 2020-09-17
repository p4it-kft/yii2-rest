<?php

namespace p4it\rest\server\resources;


use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Query;

trait RelationSearchTrait
{

    protected $_relationSearchModels = [];

    /**
     * @param $key
     * @return RelationSearchModel
     * @throws \yii\base\InvalidConfigException
     */
    public function getRelationSearchModel($key): RelationSearchModel
    {
        if (!isset($this->_relationSearchModels[$key])) {
            $this->_relationSearchModels[$key] = \Yii::createObject(self::relationSearchModels()[$key]);
        }

        return $this->_relationSearchModels[$key];
    }
}
