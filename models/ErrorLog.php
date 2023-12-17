<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "error_log".
 *
 * @property int $id
 * @property string $message
 * @property string $controller
 * @property string $action
 * @property string $params
 * @property string $created_at
 * @property string|null $updated_at
 */
class ErrorLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'error_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'controller', 'action', 'params'], 'required'],
            [['message', 'params'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['controller', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'message' => 'Message',
            'controller' => 'Controller',
            'action' => 'Action',
            'params' => 'Params',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function log(string $message, string $controller = null, string $action = null, string $params = null): void
    {
        $errorLog = new ErrorLog();
        $errorLog->message = $message;
        $errorLog->controller = $controller;
        $errorLog->action = $action;
        try {
            $errorLog->params = json_encode($params, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $errorLog->params = $e->getMessage();
        }
        $errorLog->save();
    }
}