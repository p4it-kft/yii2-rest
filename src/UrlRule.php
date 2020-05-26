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
        '{id}' => 'options',
        '' => 'options',
    ];

    public function getRules() {
        return $this->rules;
    }
}
