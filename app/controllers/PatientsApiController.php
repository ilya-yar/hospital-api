<?php

namespace app\controllers;

use app\models\PatientSearch;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\rest\CreateModelAction;
use yii\rest\IndexAction;
use yii\rest\ActiveController;
use app\models\Patient;
use yii\rest\Serializer;

class PatientsApiController extends ActiveController
{
    public $modelClass = Patient::class;

    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    public function behaviors(): array
    {
        // Если запрос совершён из браузера залогиненного пользователя (т.е. имеется авторизационная кука)
        if (Yii::$app->request->cookies->has(Yii::$app->user->identityCookie['name'])) {
            $authMethods = [];
        } else {
            // Запрос от сторонних сервисов - авторизация по Bearer токену
            $authMethods = [HttpBearerAuth::class];
        }

        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => $authMethods,
        ];
        $behaviors['ghost-access'] = [
            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        ];
        return $behaviors;
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