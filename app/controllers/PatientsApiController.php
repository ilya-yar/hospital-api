<?php

namespace app\controllers;

use app\models\PatientSearch;
use Yii;
use yii\filters\auth\CompositeAuth;
use app\rest\CreateModelAction;
use \app\filters\auth\HttpBearerUserAuth;
use yii\rest\IndexAction;
use yii\rest\ActiveController;
use app\models\Patient;
use yii\rest\Serializer;

class PatientsApiController extends ActiveController
{
    public $modelClass = Patient::class;

    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'patients',
    ];

    public function behaviors(): array
    {
        // Auth by cookie if passed in request
        if (Yii::$app->request->cookies->get(Yii::$app->user->identityCookie['name']) !== null) {
            $authMethods = [];
        } else {
            // Auth by token
            $authMethods = [HttpBearerUserAuth::class];
        }

        $behaviors = [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'authMethods' => $authMethods,
            ],
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ]
        ];

        return array_merge(parent::behaviors(), $behaviors);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => function (IndexAction $action, $filter) {
                    $searchModel = new PatientSearch();
                    return $searchModel->search(Yii::$app->request->get());
                },
            ],
            'create' => [
                // Custom create action to return saving status
                'class' => CreateModelAction::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}