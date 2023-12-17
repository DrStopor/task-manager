<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "response_log".
 *
 * @property int $id
 * @property string $response
 * @property string $created_at
 * @property int $user_id
 * @property int $message_id
 * @property int $code
 */
class ResponseLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['response'], 'required'],
            [['response'], 'string'],
            [['created_at'], 'safe'],
            [['user_id', 'message_id'], 'default', 'value' => null],
            [['user_id', 'message_id', 'code'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'response' => 'Response',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
            'message_id' => 'Message ID',
            'code' => 'Code',
        ];
    }
}
