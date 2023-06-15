<?php

namespace forms;

use Yii;
use models\Mode;
use models\User;
use yii\base\Model;
use models\Verification;
use models\DoctorProfile;
use models\VendorProfile;
use models\PatientProfile;
use components\Incrementer;
use models\PasswordHistory;

/**
 * @OA\Schema(
 *   schema="NewUser",
 *   title="NewUser",
 *   type="object",
 *   required={ "username","password","confirm_Password","first_name","middle_name","last_name","mobile_number","email_address"},
 *
 *  @OA\Property(
 *     property="username",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="password",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="confirm_password",
 *     type="string",
 * ),
 *  @OA\Property(
 *     property="type",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="first_name",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="middle_name",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="last_name",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="mobile_number",
 *     type="string",
 * ),
 * @OA\Property(
 *     property="email_address",
 *     type="string",
 * ),
 *)
 */
class RegisterForm extends Model
{
    public $username;
    public $type;
    public $password;
    public $confirm_password;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $mobile_number;
    public $email_address;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'type', 'first_name',  'middle_name', 'last_name', 'mobile_number', 'email_address' ],'required'],
            [['first_name', 'middle_name', 'last_name'],'string', 'max' => 16],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 5, 'max' => 16],

            ['email_address', 'trim'],
            ['email_address', 'required'],
            ['email_address', 'email'],
            ['email_address', 'string', 'max' => 256],
            [['email_address'], 'unique', 'targetClass' => PatientProfile::class, 'targetAttribute' => 'email_address', 'message' => 'An account with similar email address already exists.'],            

            ['mobile_number', 'trim'],
            [['mobile_number' ], 'required', 'message' => 'Mobile number is required'],
            [['mobile_number'], 'string', 'max' => 13, 'min' => 10, 'notEqual' => 'Mobile Number can only be 10 or 13 digits.'],
            [['mobile_number'], 'unique', 'targetClass' => PatientProfile::class, 'targetAttribute' => 'mobile_number', 'message' => 'An account with similar mobile number already exists.'],            


            ['type', 'exist', 'targetClass' => Mode::class, 'targetAttribute' => 'name', 'message' => 'There is no user type with simillar name.'],

            [['password','confirm_password'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ]
        ];
    }

    /**
     * Register user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->user_id   =  Incrementer::generate('users', true);
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->created_at = date('Y-m-d H:i:s');
        $user->generateApiKey();
        $user->type  =  $this->type;
        if ($user->save(false)) {

            if ($this->type == 'Patient') {
                $profile = new PatientProfile();
                $profile->user_id  = $user->getId();
                $profile->first_name  = $this->first_name;
                $profile->middle_name  = $this->middle_name;
                $profile->last_name  = $this->last_name;
                $profile->mobile_number  = $this->mobile_number;
                $profile->email_address  = $this->email_address;
                $profile->status  = 0 ;
            } elseif ($this->type == 'Doctor') {
                $profile = new DoctorProfile;
                $profile->doctor_id = 'DR' . Incrementer::generate('doctor_profile', true,'y');
                $profile->user_id  = $user->getId();
                $profile->first_name  = $this->first_name;
                $profile->middle_name  = $this->middle_name;
                $profile->last_name  = $this->last_name;
                $profile->mobile_number  = $this->mobile_number;
                $profile->email_address  = $this->email_address;
                $profile->status  = 0 ;
            } elseif ($this->type == 'Vendor'){
                $profile = new VendorProfile;
                $profile->user_id  = $user->getId();
                $profile->first_name  = $this->first_name;
                $profile->middle_name  = $this->middle_name;
                $profile->last_name  = $this->last_name;
                $profile->mobile_number  = $this->mobile_number;
                $profile->email_address  = $this->email_address;
                $profile->status  = 0 ;
            }else{
                return "Unknown Designation";

            }
            if ($profile->save(false)){
                $verification = new Verification;
                $verification->user_id = $user->getId();
                $verification->mobile_number = 0;
                $verification->email_address = 0;                
                if ($verification->save(false)){
                    $passwordHistory = new PasswordHistory;
                    $passwordHistory->user_id = $user->getId();
                    $passwordHistory->password = md5($this->password);
                    $passwordHistory->save(false);
                    return $passwordHistory->save() ? $user : false && $this->sendEmail($profile);
                }
                return false;
            }
            return false;
        }
        return false;      
    }

    protected function sendEmail($profile)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $profile]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email_address)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
    
}

