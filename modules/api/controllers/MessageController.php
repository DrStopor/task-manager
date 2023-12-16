<?php

namespace app\modules\api\controllers;

use app\models\Message;
use app\modules\api\resource\ContactResource;
use app\modules\api\resource\StatusResource;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use app\modules\api\resource\MessageResource;
use yii\web\Response;

class MessageController extends ActiveController
{
    public $modelClass = MessageResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    final public function actionRequest()
    {
        return match (Yii::$app->request->method) {
            'GET' => $this->getRequests(),
            'POST' => $this->createMessage(),
            'PUT' => $this->setComment(),
            default => $this->prepareResponse([], 404, '[{-_-}] ZZZzz zz z...')
        };
    }

    public function getRequests(): Response|\yii\console\Response
    {
        $status = Yii::$app->request->get('status');
        if ($status) {
            $model = MessageResource::find()
                ->where(['LOWER(status.name)' => strtolower($status)])
                ->joinWith('status')
                ->with('contact')
                ->all();
            return $this->prepareResponse($model);
        }
        // TODO отдавать только ACTIVE
        $model = MessageResource::find()->with('contact')->with('status')->all();

        return $this->prepareResponse($model);
    }

    public function setComment(): Response|\yii\console\Response
    {
        $id = Yii::$app->request->post('id');
        $comment = Yii::$app->request->post('comment');
        $statusName = Yii::$app->request->post('status');
        $userId = Yii::$app->user->id;
        $status = StatusResource::findOne(['name' => $statusName]);
        if (!$status) {
            return $this->prepareResponse([], 400, 'не удалось сохранить');
        }
        $message = MessageResource::findOne($id);
        if (!$message) {
            return $this->prepareResponse([], 400, 'не удалось сохранить');
        }

        $message->comment = $comment;
        $message->status_id = $status->id;
        $message->user_id = $userId;
        if ($message->save()) {
           $this->prepareResponse($message);
        }
        return $this->prepareResponse([], 400, 'не удалось сохранить');
    }

    public function createMessage()
    {
        $extId = Yii::$app->request->post('id');
        $author = Yii::$app->request->post('name');
        $email = Yii::$app->request->post('email');
        $message = Yii::$app->request->post('message');

        $emailValidator = new \yii\validators\EmailValidator();
        if (!$emailValidator->validate($email)) {
            return ['error' => 'не удалось сохранить 1'];
        }

        $contact = ContactResource::findOne(['email' => $email]);
        if (!$contact) {
            $contact = new ContactResource();
            $contact->name = $author;
            $contact->email = $email;
            if (!$contact->save()) {
                return ['error' => 'не удалось сохранить 2'];
            }
        }

        $messageModel = new MessageResource();
        $messageModel->contact_id = $contact->id;
        $messageModel->message = $message;
        $messageModel->ext_id = $extId;

        if (!$messageModel->validate()) {
            return ['error' => 'не удалось сохранить 3'];
        }

        if ($messageModel->save()) {
            return ['message' => 'success'];
        }
        return ['error' => 'не удалось сохранить 4'];
    }

    /**
     * @param array|Message $data
     * @param int $code
     * @param string|null $error
     * @return Response|\yii\console\Response
     */
    private function prepareResponse(array|Message $data, int $code = 200, string $error = null): Response|\yii\console\Response
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
        $response->statusCode = $code;
        if ($error) {
            $response->data['error'] = $error;
        }
        return $response;
    }

    public function actionNotFound()
    {
        return $this->prepareResponse([], 404, '[{-_-}] ZZZzz zz z...');
    }
}