<?php
namespace p4it\rest\server;

use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

class UpdateAction extends \yii\rest\UpdateAction
{
    /**
     * The function to be used to save the model. If not set, {@link ActiveRecord::save()} will be used by default.
     * Return value of callable should be similar to {@link ActiveRecord::save()} or {@link ActiveRecord::update()},
     * possibly with validation as well.
     * @var null|callable
     */
    public $save;

    public function init()
    {
        if (!$this->save) {
            $this->save = [$this,'save'];
        }

        parent::init();
    }

    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $result = call_user_func($this->save,$model,$this);
        if ($result === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        if(!$model->hasErrors()) {
            $model->refresh();
        }

        return $model;
    }

    protected function save(ActiveRecord $model)
    {
        return $model->save();
    }
}
