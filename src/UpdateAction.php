<?php
namespace p4it\rest\server;

use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

class UpdateAction extends \yii\rest\UpdateAction
{
    public function run($id)
    {
        $model = parent::run($id);

        if(!$model->hasErrors()) {
            $model->refresh();
        }

        return $model;
    }
}
