<?php

namespace modules\main\controllers;

use Yii;
use models\Token;
use forms\LoginForm;
use forms\RegisterForm;
use components\Controller;
use models\VerifyEmailForm;
use yii\filters\AccessControl;
use models\ResendVerificationEmailForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
class GuestController extends Controller
{
   
    public function actionIndex()
    {
        $params = Yii::$app->params;
        return [
            'name' => $params['name'],
            'description' => $params['description'],
            'version' => $params['version'],
            'baseUrl' => $this->baseUrl()
        ];
    }

    /**
     * Login
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $dataRequest['LoginForm'] = Yii::$app->request->post();
        $model = new LoginForm();
        if ($model->load($dataRequest) && ($result = $model->login())) {
            return $this->apiSuccess($result);
        }

        return $this->apiValidated($model->errors);
    }

    /**
     * Register
     *
     * @return mixed
     */
    public function actionRegister()
    {
        $dataRequest['RegisterForm'] = Yii::$app->request->getBodyParams();
        $model = new RegisterForm();
        if ($model->load($dataRequest)) {
            if (($user = $model->register())) {
                return  $this->apiGenerated($user);
                // return Token::sendToken($user->id) ? $this->apiGenerated($user, "Thank you for registration. Please check your phone a code has been sent to you to verify your mobile number.") : false;
            }
        }

        return $this->apiValidated($model->errors);
    }

    public function actionVerify()
    {
        $dataRequest['Token'] = Yii::$app->request->getBodyParams();
        $model = new Token();
       
        if ($model->load($dataRequest) && ($model->validate() && $otp = $model->verify())) {
            return $this->apiGenerated($otp);
        }
        return $this->apiValidated($model->errors);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->apiGenerated($user);
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->apiValidated($model->errors);
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->apiGenerated($user);
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }
        return $this->apiValidated($model->errors);

    }
}
