<?php

namespace models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class PasswordHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%password_history}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'password'], 'required'],
            [['user_id'], 'integer'],
            [['password'], 'string', 'max' => 32],
        ];
    }
}