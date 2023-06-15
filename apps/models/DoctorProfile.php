<?php

namespace models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @OA\Schema(
 *   schema="CreateDoctorProfile",
 *   title="DoctorProfile",
 *   type="object",
 *   required={"first_name","last_name","mobile","email"},
 *   
 * @OA\Property(
 *    property="first_name",
 *    type="string",
 *   ),
 *  @OA\Property(
 *    property="last_name",
 *    type="string",
 *   ),
 *  @OA\Property(
 *    property="mobile",
 *    type="string",
 *   ),
 * @OA\Property(
 *    property="email",
 *    type="string",
 *   ),
 * @OA\Property(
 *    property="content",
 *    type="string",
 *   ),
 * )
 */

 /**
 * @OA\Schema(
 *   schema="UpdateDoctorProfile",
 *   type="object",
 *   required={"first_name","last_name","mobile","email"},
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/CreateDoctorProfile"),
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="DoctorProfile",
 *   type="object",
 *   required={"first_name","last_name","mobile","email"},
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/CreateDoctorProfile"),
 *       @OA\Schema(
 *           required={"Id"},
 *           @OA\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

/**
 * @OA\RequestBody(
 *     request="DoctorProfile",
 *     description="Doctor Profile portion that needs to be added to the article",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/CreateDoctorProfile"),
 *     @OA\MediaType(
 *         mediaType="application/xml",
 *         @OA\Schema(ref="#/components/schemas/CreateDoctorProfile")
 *     )
 * )
 */

class DoctorProfile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%doctor_profile}}';
    }

    public function fields()
    {
        return [
            'id',
            'user ID' => function () {
                return $this->user_id;
            }, 'First Name' => function () {
                return $this->first_name;
            }, 'Middle Name' => function () {
                return $this->middle_name;
            }, 'Last Name' => function () {
                return $this->last_name;
            }, 'mobile number' => function () {
                return $this->mobile_number;
            }, 'email' => function () {
                return $this->email_address;
            }
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,

        ];
    }
    public function rules()
    {
        return [
            [['first_name', 'middle_name','last_name','email_address'], 'required'],
        
            [['first_name', 'last_name','middle_name'], 'string', 'max' => 16],

            ['email_address', 'trim'],
            ['email_address', 'email'],
            ['email_address', 'string', 'max' => 256],
            [['email_address'], 'unique', 'targetClass' => PatientProfile::class, 'targetAttribute' => 'email_address', 'message' => 'An account with similar email address already exists.'],            

            ['mobile_number', 'trim'],
            [['mobile_number' ], 'required', 'message' => 'Mobile number is required'],
            [['mobile_number'], 'string', 'max' => 13, 'min' => 10, 'notEqual' => 'Mobile Number can only be 10 or 13 digits.'],
            [['mobile_number'], 'unique', 'targetClass' => PatientProfile::class, 'targetAttribute' => 'mobile_number', 'message' => 'An account with similar mobile number already exists.'],            

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
}