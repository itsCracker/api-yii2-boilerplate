<?php

namespace models;

use models\PatientProfile;
use Yii;
use models\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email_address;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email_address', 'trim'],
            ['email_address', 'required'],
            ['email_address', 'email'],
            ['email_address', 'exist',
                'targetClass' => PatientProfile::class,
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = PatientProfile::findOne([
            'email_address' => $this->email_address,
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
