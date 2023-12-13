<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request_log".
 *
 * @property int $id
 * @property string $ip
 * @property string $method
 * @property string $action
 * @property string|null $params
 * @property string $created_at
 */
class RequestLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'method', 'action'], 'required'],
            [['params'], 'string'],
            [['created_at'], 'safe'],
            [['ip', 'method', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'method' => 'Method',
            'action' => 'Action',
            'params' => 'Params',
            'created_at' => 'Created At',
        ];
    }
}
