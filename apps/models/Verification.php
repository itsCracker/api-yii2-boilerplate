<?php

namespace models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Verification extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%verification}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}