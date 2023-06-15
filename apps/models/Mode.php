<?php

namespace models;

use Yii;
use yii\db\ActiveRecord;
use components\Incrementer;
use yii\behaviors\TimestampBehavior;

/**
 * @OA\Schema(
 *   schema="CreateType",
 *   title="Type",
 *   type="object",
 *   required={"name"},
 *   
 * @OA\Property(
 *    property="name",
 *    type="string",
 *   ),
 * )
 */

 /**
 * @OA\Schema(
 *   schema="UpdateType",
 *   type="object",
 *   required={"name"},
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/CreateType"),
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="Type",
 *   type="object",
 *   required={"name"},
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/CreateType"),
 *       @OA\Schema(
 *           required={"Id"},
 *           @OA\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

/**
 * @OA\RequestBody(
 *     request="Type",
 *     description="Type portion that needs to be added to the article",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/CreateType"),
 *     @OA\MediaType(
 *         mediaType="application/xml",
 *         @OA\Schema(ref="#/components/schemas/CreateType")
 *     )
 * )
 */
/**
 * This is the model class for table "type".
 *
 * @property int $id
 * @property string|null $name
 */
class Mode extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mode';
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
            
            [['name'],'required'],
            ['name', 'trim'],
            ['name', 'unique', 'targetClass' => Mode::class, 'targetAttribute' => 'name', 'message' => 'This name has already been taken.'],
            [['name'], 'string', 'max' => 8],

        ];
    }

    public function mode()
    {
        if (!$this->validate()) {
            return false;
        }

        $mode = new Mode();
        $mode->mode_id   =  Incrementer::generate('mode', true);
        $mode->name = $this->name;
        $mode->save(false);
        return $mode->save() ? $mode : false;
        
    }

}
