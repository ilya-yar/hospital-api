<?php

namespace app\filters\auth;

class HttpBearerUserAuth extends \yii\filters\auth\HttpBearerAuth
{
    public function authenticate($user, $request, $response)
    {
        $identity = parent::authenticate($user, $request, $response);

        if ($identity) {
            \Yii::$app->user->setIdentity($identity);
        }

        return $identity;
    }
}
