<?php

namespace p4it\rest\server\resources;


use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Query;

class RelationSearchModel extends Component {

    /** @var ActiveRecord */
    public $searchModelClass;

    /** @var string */
    public $relation;

    /**
     * @var Query
     */
    public $query;

    public function setQuery(Query $query)
    {
        $this->query = $query;
    }

    public function mergeQuery(Query $query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        if($this->query !== null) {
            $this->query;
        }

        if($this->relation && $this->searchModelClass) {
            $model = new $this->searchModelClass();
            $model->getRelation($this->relation);
        }

        return $this->query ?? $this->searchModelClass::find();
    }
}
