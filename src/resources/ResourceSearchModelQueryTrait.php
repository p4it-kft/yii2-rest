<?php

namespace p4it\rest\server\resources;

use p4it\rest\server\data\ActiveDataFilter;

trait ResourceSearchModelQueryTrait
{
    protected function getRelationSearchModelQueries($action, $requestParams) {
        $relationSearchModelQueries = [];
        foreach ($action->relationDataFilters as $key => $relationDataFilter) {
            $relationDataFilter = \Yii::createObject($relationDataFilter);
            if ($relationDataFilter->load($requestParams)) {
                $filter = $relationDataFilter->build();
                if ($filter === false) {
                    return $relationDataFilter;
                }
            } else {
                continue;
            }

            if ($relationDataFilter->searchModel instanceof ResourceSearchInterface) {
                $queryExtraField = $relationDataFilter->searchModel->searchQuery();
            } else {
                /* @var $modelClass \yii\db\BaseActiveRecord */
                $modelClass = $relationDataFilter->searchModel;

                $queryExtraField = $modelClass::find();
            }

            if($relationDataFilter instanceof ActiveDataFilter && $relationDataFilter->splitFilter) {
                if (isset($filter['whereFilter']) && $filter['whereFilter']) {
                    $queryExtraField->andWhere($filter['whereFilter']);
                }
                if (isset($filter['havingFilter']) && $filter['havingFilter']) {
                    $queryExtraField->andHaving($filter['havingFilter']);
                }
            } elseif (!empty($filter)) {
                $queryExtraField->andWhere($filter);
            }

            $relationSearchModelQueries[$key] = $queryExtraField;
        }

        return $relationSearchModelQueries;
    }
}
