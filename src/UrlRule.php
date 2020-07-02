<?php
namespace p4it\rest\server;

class UrlRule extends \yii\rest\UrlRule
{
    public $patterns = [
        'PUT,PATCH {id}' => 'update',
        'DELETE {id}' => 'delete',
        'GET,HEAD {id}' => 'view',
        'POST' => 'create',
        'GET,POST search' => 'index', //proxy action to be able to accept post request on index action with complicated filters in request body
        'GET describe' => 'describe',
        'GET,HEAD' => 'index',
        'GET,POST {columnName}/values' => 'values',
        'GET,POST {columnName}/{columnId}/values' => 'values',
        '{id}' => 'options',
        '' => 'options',
    ];

    public function getRules() {
        return $this->rules;
    }
    
    public $tokens = [
        '{id}' => '<id:\\d[\\d,]*>',
        '{columnName}' => '<columnName:\\w[\\w,]*>',
        '{columnId}' => '<columnId:\\w[\\w,]*>',
    ];
}
