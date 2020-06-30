<?php

namespace p4it\rest\server\resources;

use pappco\yii2\components\ActiveQuery;

interface ResourceSearchInterface
{
    /**
     * @return ActiveQuery
     */
    public function searchQuery();
}
