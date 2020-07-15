<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace p4it\rest\server;

/**
 * @property-read array[] $requestedFields
 */
class Serializer extends \yii\rest\Serializer
{
    protected function getRequestedFields()
    {
        $fields = $this->request->get($this->fieldsParam);
        $expand = $this->request->get($this->expandParam);

        if(!$fields && !$expand && $this->request->isPost) {
            $fields = $this->request->post($this->fieldsParam);
            $expand = $this->request->post($this->expandParam);
        }

        return [
            is_string($fields) ? preg_split('/\s*,\s*/', $fields, -1, PREG_SPLIT_NO_EMPTY) : [],
            is_string($expand) ? preg_split('/\s*,\s*/', $expand, -1, PREG_SPLIT_NO_EMPTY) : [],
        ];
    }
}
