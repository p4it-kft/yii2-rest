<?php
namespace p4it\rest\server;

class CreateAction extends \yii\rest\CreateAction
{
    public function run()
    {
        $model = parent::run();

        if(!$model->hasErrors()) {
            $model->refresh();
        }

        return $model;
    }
}
