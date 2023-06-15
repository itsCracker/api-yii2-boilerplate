<?php

namespace models;

use Yii;
use models\User;
use components\Sms;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;


/**
 * @OA\Schema(
 *   schema="Token",
 *   type="object",
 *   required={"user_id", "token", "updated_at", "created_at"},
 *  
 * @OA\Property(
 *     property="user_id",
 *     type="integer",
 * ), 
 * 
 * @OA\Property(
 *     property="token",
 *     type="string",
 * ),
 * 
 * @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="datetime",
 *   ),
 * 
 *    @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="datetime",
 *   ),
 *)
 */

class Token extends ActiveRecord
{
    public $mobile_number;
    public $mobile;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['token'], 'required', 'message' => 'Verification code is required.'],
            [['token'], 'string', 'length' => 6],
            ['token', 'validateOtp']
        ];
    }

    public function validateOtp($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = self::findOne(['user_id' => $this->user_id, 'token'  =>  $this->token]);            
            if (is_null($model)) {
                $this->addError($attribute, 'Invalid OTP provided.');
            } else {
                if ($model->status == 1) {
                    $this->addError($attribute, 'This OTP has been used.');
                } else {
                    $expiry = '2 minutes';
                    if (strtotime('+' . $expiry,  $model->created_at) < time()) {
                        $this->addError($attribute, 'The OTP provided has expired, please request a new OTP');
                    }
                }
            }
            
        }
        return "validation errors";
    }

    public function fields()
    {
        return ['user_id', 'token', 'type'];
    }

    public static  function sendToken($user_id, $silent = false)
    {
        $model = new self;
        $model->user_id = $user_id;
        $user = PatientProfile::findOne(['user_id' => $user_id]);
        $otp = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $expiry = '30 minutes';
        $model->mobile_number = '+254' . substr($user->mobile_number, -9);
        $model->token = $otp;

        if ($model->save(false)) {
            $message = 'Your verification code is ' . $otp . ' and will expire in the next ' . $expiry;
            Sms::sendOtp(['to' => $model->mobile_number, 'msg' => $message]);
            if($silent){
                return md5($otp). '_' . $model->created_at;
            }
            else{
            return ['expiry' => date("His", strtotime('+' . $expiry,  $model->created_at))];
            }
        }
    }

    public function verify()
    {
        $model = self::findOne(['user_id' => $this->user_id, 'token'  =>  $this->token]);    
        $x = "mobile verify";
        $model->type =  $x;       
        $model->status = true;
        if ($model->save(false)) {
            $user = Verification::findOne(['user_id' => $this->user_id]);
            $user->mobile_number = true;
            if ($user->save(false)) {
                return ['mobile' => $user->mobile, 'status' => "Mobile verified"];
            }
            return "Mobile not verified";
        }
        return "OTP verification failed";
    }
}
