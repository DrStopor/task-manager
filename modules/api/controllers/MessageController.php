<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use app\modules\api\resource\MessageResource;

class MessageController extends ActiveController
{
    public $modelClass = MessageResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class
        ];
        $behaviors['authenticator'] = ['class' => \yii\filters\auth\HttpBearerAuth::class];
        //$behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actionRequest()
    {
        return match (Yii::$app->request->method) {
            'GET' => $this->actionGetRequests(),
            'POST' => $this->actionSetComment(),
            'PUT' => $this->actionRecivedMessage(),
            default => ['error' => '[{-_-}] ZZZzz zz z...'],
        };
    }

    public function actionRecivedMessage()
    {

        $id = Yii::$app->request->post('id');
        $author = Yii::$app->request->post('name');
        $email = Yii::$app->request->post('email');
        $message = Yii::$app->request->post('message');

        $messageModel = new MessageResource();
        $messageModel->contact->name = $author;
        $messageModel->contact->email = $email;
        $messageModel->message = $message;
        $messageModel->ext_id = $id;
        if ($messageModel->save()) {
            return ['message' => 'success'];
        }
        return ['error' => 'не удалось сохранить'];
    }

    public function actionGetRequest($id)
    {
        $message = MessageResource::findOne($id);
        if ($message) {
            return $message;
        }
        return ['error' => 'не удалось найти сообщение'];
    }

    public function actionGetRequests()
    {
        $model = MessageResource::find()->all();
        return $model;
    }

    public function actionSetComment()
    {
        $id = Yii::$app->request->post('id');
        $comment = Yii::$app->request->post('comment');
        $message = MessageResource::findOne($id);
        if ($message) {
            $message->comment = $comment;
            $message->updated_at = time();
            if ($message->save()) {
                return $this->prepareResponse(['message' => 'success']);
            }
            return $this->prepareResponse(['error' => 'не удалось сохранить']);
        }
        return $this->prepareResponse(['error' => 'не удалось найти сообщение']);
    }

    private function prepareResponse($data)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;
        return $response;
    }
}