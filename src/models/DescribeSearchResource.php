<?php

namespace p4it\rest\server\models;

use p4it\rest\server\data\ActiveDataFilter;
use pappco\yii2\helpers\ArrayHelper;
use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeSearchResource extends Model
{

    public $modelClass;

    public $filter;
    public $order;
    public $sort;


    public function fields()
    {
        return [
            'filter',
            'order',
            'sort',
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->initFilters();
    }

    protected function initFilters()
    {
        $filter = new ActiveDataFilter(['searchModel' => $this->modelClass]);
        $searchAttributeTypes = $filter->getSearchAttributeTypes();

        $operationTypes = ArrayHelper::merge($filter->extraOperatorTypes, $filter->operatorTypes);
        $searchAttributeTypeOperationTypes = [];
        foreach ($searchAttributeTypes as $searchAttribute => $searchAttributeType) {
            $searchAttributeTypeOperationTypes[$searchAttribute] = array_keys(array_filter($operationTypes,
                static function ($operationType) use ($searchAttributeType) {
                    if ($operationType === '*') {
                        return true;
                    }

                    return in_array($searchAttributeType, $operationType, true);
                }));
        }

        $this->filter = new DescribeContentSchema();

        foreach ($searchAttributeTypeOperationTypes as $searchAttribute => $type) {
            $this->filter->addProperty($searchAttribute, 'possible operators: '.implode(',', $type), DescribeType::getType($searchAttributeTypes[$searchAttribute] ?? null));
        }
    }

}