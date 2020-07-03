<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace p4it\rest\server;

use p4it\rest\server\data\TransformActiveDataProvider;
use p4it\rest\server\resources\ValueResource;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class ValuesAction extends \yii\rest\IndexAction
{
    public $transform;
    
    /**
     * @return ActiveDataProvider
     */
    public function run($columnName = null, $columnId = null)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $dataprovider = $this->prepareDataProvider();

        if ($this->dataFilter !== null) {
            $model = $this->dataFilter->getSearchModel();
        } else {
            $model = new $this->modelClass();
        }

        if($columnId === null) {
            $columnId = $columnName;
        }
        
        if(!$model->isAttributeSafe($columnName)) {
            throw new ServerErrorHttpException('Column Name is not a safe attribute.');
        }
        
        if(!$model->isAttributeSafe($columnId)) {
            throw new ServerErrorHttpException('Column Id is not a safe attribute.');
        }

        $dataprovider->query->groupBy([$columnName])->select([$columnName => 'name']);

        if ($this->transform !== null && is_callable($this->transform)) {
            $transform = $this->transform;
        } else {
            $transform = static function($row) {
                return new ValueResource(
                    ['name' => $row['name'], 'id' => $row['id'], 'count' => $row['count']]
                );
            };
        }
        
        return \Yii::createObject([
            'class' => TransformActiveDataProvider::class,
            'query' => $dataprovider->query->groupBy([$columnName])->asArray()->select(['name' => $columnName, 'count' => 'count(*)', 'id' => $columnId]),
            'transform' => $transform,
            'key' => 'id',
            'pagination' => [
                'params' => $dataprovider->getPagination()->params,
            ],
            'sort' => [
                'params' => $dataprovider->getSort()->params,
            ],
        ]);

        return $dataprovider;
    }
}
