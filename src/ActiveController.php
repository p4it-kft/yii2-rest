<?php

namespace p4it\rest\server;

use p4it\rest\server\data\ActiveDataFilter;
use p4it\rest\server\data\ActiveDataProvider;
use p4it\rest\server\resources\RelationSearchModel;
use p4it\rest\server\resources\ResourceSearchInterface;
use p4it\rest\server\resources\ResourceSearchModelQueryTrait;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

class ActiveController extends \yii\rest\ActiveController
{
    use ResourceSearchModelQueryTrait;
    public $searchModelClass;

    /**
     * @inheritdoc
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => [$this, 'prepareDataProvider'],
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => OptionsAction::class,
            ],
            'describe' => [
                'class' => DescribeAction::class,
            ],
            'values' => [
                'class' => ValuesAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => [$this, 'prepareDataProvider'],
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];

        if ($this->searchModelClass) {
            if(method_exists($this->searchModelClass, 'attributeMap')) {
                $attributeMap = $this->searchModelClass::attributeMap();
            }
            if(method_exists($this->searchModelClass, 'attributeHaving')) {
                $attributeHaving = $this->searchModelClass::attributeHaving();
            }

            $actions['index']['dataFilter'] = [
                'class' => ActiveDataFilter::class,
                'searchModel' => $this->searchModelClass,
                'attributeMap' => $attributeMap??[],
                'attributeHaving' => $attributeHaving??[],
                'splitFilter' => isset($attributeHaving),
            ];

            $actions['values']['dataFilter'] = [
                'class' => ActiveDataFilter::class,
                'searchModel' => $this->searchModelClass,
                'attributeMap' => $attributeMap??[],
                'attributeHaving' => $attributeHaving??[],
                'splitFilter' => isset($attributeHaving),
            ];
        }

        if (method_exists($this->modelClass, 'relationSearchModels')) {
            foreach ($this->modelClass::relationSearchModels() as $key => $relationSearchModel) {
                $attributeMap = [];
                $attributeHaving = [];

                /** @var RelationSearchModel $relationSearchModel */
                $relationSearchModel = Yii::createObject($relationSearchModel);
                if(method_exists($relationSearchModel->searchModelClass, 'attributeMap')) {
                    $attributeMap = $relationSearchModel->searchModelClass::attributeMap();
                }
                if(method_exists($relationSearchModel->searchModelClass, 'attributeHaving')) {
                    $attributeHaving = $relationSearchModel->searchModelClass::attributeHaving();
                }

                $actions['index']['relationDataFilters'][$key] = [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $relationSearchModel->searchModelClass,
                    'filterAttributeName' => 'filter_'.$key,
                    'attributeMap' => $attributeMap??[],
                    'attributeHaving' => $attributeHaving??[],
                    'splitFilter' => (bool)$attributeHaving,
                ];

                $actions['view']['relationDataFilters'][$key] = [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $relationSearchModel->searchModelClass,
                    'filterAttributeName' => 'filter_'.$key,
                    'attributeMap' => $attributeMap??[],
                    'attributeHaving' => $attributeHaving??[],
                    'splitFilter' => (bool)$attributeHaving,
                ];
            }
        }

        return $actions;
    }

    /**
     * ha action-onként külön jogosultság kezelés van, akkor ezt kell kifejteni a controller szinten is
     * tovább szűkítve a már megadott searchQuery-t
     *
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws UnauthorizedHttpException
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (!$model || $this->searchModelClass === null) {
            return parent::checkAccess($action, $model, $params);
        }

        /* item protection */
        $searchModelClass = $this->searchModelClass;
        $searchModel = new $searchModelClass();

        if ($searchModel instanceof ResourceSearchInterface) {
            $query = $searchModel->searchQuery();
            $wrappedQuery = $searchModel->wrapQuery??false;
            if($wrappedQuery) {
                $model = $query->andWhere($model->getPrimaryKey(true))->one();
            } else {
                //nincs wrap, ezért kell az aktuális tabla nevet használni ami nem biztos hogy jó. bár elvileg az aliasolásnak kéne automatice működnie
                $primaryKeys = [];
                foreach ($model->getPrimaryKey(true) as $key => $value) {
                    $primaryKeys[$model::tableName().'.'.$key] = $value;
                }
                $model = $query->andWhere($primaryKeys)->one();
            }
            if ($model === null) {
                throw new UnauthorizedHttpException();
            }
        }

        return parent::checkAccess($action, $model, $params); // TODO: Change the autogenerated stub
    }

    /**
     * @param IndexAction $action
     * @param $filter
     * @return object|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function prepareDataProvider($action, $filter)
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        if ($action->dataFilter->searchModel instanceof ResourceSearchInterface) {
            $query = $action->dataFilter->searchModel->searchQuery();
        } else {
            /* @var $modelClass \yii\db\BaseActiveRecord */
            $modelClass = $this->modelClass;

            $query = $modelClass::find();
        }

        if($action->dataFilter instanceof ActiveDataFilter && $action->dataFilter->splitFilter) {
            if (isset($filter['whereFilter']) && $filter['whereFilter']) {
                $query->andWhere($filter['whereFilter']);
            }
            if (isset($filter['havingFilter']) && $filter['havingFilter']) {
                $query->andHaving($filter['havingFilter']);
            }
        } elseif (!empty($filter)) {
            $query->andWhere($filter);
        }

        $relationSearchModelQueries = $this->getRelationSearchModelQueries($action, $requestParams);

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'relationSearchModelQueries' => $relationSearchModelQueries,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }



    /**
     * we could maybe maintain options as well based on this.
     *
     * @param array $actions
     * @param mixed ...$actionsToUnset
     */
    protected function unsetActions(array &$actions, ...$actionsToUnset): void
    {
        foreach ($actionsToUnset as $item) {
            unset($actions[$item]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'POST'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            'values' => ['GET', 'POST'],
            'describe' => ['GET'],
        ];
    }
}
