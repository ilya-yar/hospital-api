<?php

namespace app\rest;

use Yii;
use yii\helpers\Url;
use yii\rest\CreateAction;

class CreateModelAction extends CreateAction
{
    /**
     * @return array
     * @return array creation status.
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $status = false;
        $errors = [];
        $id = null;

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', $model->getPrimaryKey(true));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
            $status = true;
        } else {
            $errors = [$model->errors];
        }

        return ['status' => $status, 'errors' => $errors, 'id' => $id];
    }
}
