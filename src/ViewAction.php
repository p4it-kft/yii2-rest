<?php
namespace p4it\rest\server;

use p4it\rest\server\resources\RelationSearchInterface;
use p4it\rest\server\resources\ResourceSearchModelQueryTrait;
use Yii;
use yii\data\DataFilter;

class ViewAction extends \yii\rest\ViewAction
{

    use ResourceSearchModelQueryTrait;

    /**
     * @var DataFilter[]
     */
    public $relationDataFilters = [];

    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if($model instanceof RelationSearchInterface) {
            $requestParams = Yii::$app->getRequest()->getBodyParams();
            if (empty($requestParams)) {
                $requestParams = Yii::$app->getRequest()->getQueryParams();
            }

            $relationSearchModelQueries = $this->getRelationSearchModelQueries($this, $requestParams);

            foreach ($relationSearchModelQueries as $key => $relationSearchModelQuery) {
                $relationsSearchModel = $model->getRelationSearchModel($key);
                $relationsSearchModel->setQuery(clone $relationSearchModelQuery);
            }
        }

        return $model;
    }
}
