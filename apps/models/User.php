<?php

namespace models;

use Yii;
use components\UserJwt;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
/**
 * User model
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $api_key
 * @property integer $status
 * @property string $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User ",
 * 
 *  @OA\Property(
 *     property="username",
 *     type="string",
 *   ),
 * 
 *  @OA\Property(
 *     property="password_hash",
 *     type="string",
 *   ),
 * 
 *  @OA\Property(
 *     property="auth_key",
 *     type="string",
 *   ),
 *  @OA\Property(
 *     property="api_key",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="type",
 *     type="string",
 *   ),
 *  @OA\Property(
 *     property="status",
 *     format="int32",
 *     type="integer",
 *     description="status",
 *   ),
 * 
 *  @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="datetime",
 *   ),
 * 
 *  @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="datetime",
 *    ),
 * )
 */

class User extends ActiveRecord implements IdentityInterface
{
    public $token;
    public $password;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    use UserJwt;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return ['user_id','username', 'token'];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return null or static
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->select('user_id, username,auth_key, password_hash,  api_key, type, created_at, updated_at')
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['OR', ['username' => $username]])
            ->one();
    }

     /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->token = $this->getJWT();

        /* change format date */
        $parse = Yii::$app->formatter;
        $this->created_at = $parse->asDate($this->created_at, 'php:Y-m-d H:i:s');
        $this->updated_at = $parse->asDate($this->updated_at, 'php:Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->token = $this->getJWT();

        /* change format date */
        $parse = Yii::$app->formatter;
        $this->created_at = $parse->asDate($this->created_at, 'php:Y-m-d H:i:s');
        $this->updated_at = $parse->asDate($this->updated_at, 'php:Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

      /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
  

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new api key
     */
    public function generateApiKey()
    {
        $this->api_key = md5($this->username . $this->password . $this->created_at);
        
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function patient($user_id)
    {
        return  PatientProfile::find()
        ->select('patient_profile_id, first_name,  middle_name, last_name, mobile_number, email_address,created_at')
        ->where(['user_id' => $user_id])->asArray()
        ->one();
        
    }
}