<?php

namespace components;

use Yii;
use yii\filters\auth\HttpBearerAuth;


/**
 * This Controller  extends from \yii\rest\Controller
 *
 */
class Controller extends \yii\rest\Controller
{
    use TraitController;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['authenticator']['except'] = ['logout', 'send-token', 'login',
            'register', 'verify'];
        return $behaviors;
    }

    /**
     * Api Validated error response
     */
    public function apiGenerated($data, $message = false)
    {
        Yii::$app->response->statusCode = 201;
        return [
            'statusCode' => 201,
            'message' => $message ? $message : 'Created successfully',
            'data' => $data
        ];
    }

     /**
     * Api Validated error response
     */
    public function apiValidated($errors, $message = false)
    {
        Yii::$app->response->statusCode = 422;
        return [
            'statusCode' => 422,
            'name' => 'ValidateErrorException',
            'message' => $message ? $message : 'Error validation',
            'errors' => $errors
        ];
    }

    /**
     * Api data retrieval  success response
     */
    public function apiSuccess($data, $total = 0,$message = false){

        Yii::$app->response->statusCode = 200;

        return [
            'statusCode' => 200,
            'message' => $message ? $message : 'success',
            'data' => $data,
            'total' => $total
        ];

    }

    /**
     * Api Updated and delete response
     */
    public function apiUpdateDelete($data, $message = false)
    {
        Yii::$app->response->statusCode = 202;
        return [
            'statusCode' => 202,
            'message' => $message ? $message : 'Success',
            'data' => $data
        ];
    }

}
