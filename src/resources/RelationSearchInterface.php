<?php

namespace p4it\rest\server\resources;


use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Query;

interface RelationSearchInterface
{
    public function getRelationSearchModel($key):RelationSearchModel;
    public static function relationSearchModels();
}
