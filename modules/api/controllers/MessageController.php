<?php

namespace app\modules\api\controllers;

use app\models\Message;
use app\modules\api\helpers\Helper;
use app\modules\api\helpers\ResponseHelper;
use app\modules\api\resource\ContactResource;
use app\modules\api\resource\MailQueueResource;
use app\modules\api\resource\MessageResource;
use app\modules\api\resource\StatusResource;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\Response;

class MessageController extends ActiveController
{
    public $modelClass = MessageResource::class;
    private Comment $comment;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->comment = new Comment();
    }

    final public function behaviors(): array
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

    final public function actionRequest(): array|Response|\yii\console\Response
    {
        return match (Yii::$app->request->method) {
            'GET' => $this->getRequests(),
            'POST' => $this->createMessage(),
            'PUT' => $this->setComment(),
            default => ResponseHelper::prepareResponse([], 405, '[{-_-}] ZZZzz zz z...')
        };
    }

    final public function getRequests(): Response|\yii\console\Response
    {
        $status = Yii::$app->request->get('status');
        if ($status) {
            $model = MessageResource::getAllMessagesByStatusName($status);
            return ResponseHelper::prepareResponse($model);
        }
        // TODO отдавать только ACTIVE
        $model = MessageResource::getAllMessages();

        return ResponseHelper::prepareResponse($model);
    }

    final public function setComment(): Response|\yii\console\Response
    {
        return $this->comment->replyToRequest();
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

    public function actionNotFound()
    {
        return ResponseHelper::prepareResponse([], 404, '[{-_-}] ZZZzz zz z...');
    }

    public function beforeAction($action)
    {
        $requestLog = new \app\models\RequestLog();
        $requestLog->method = Yii::$app->request->method;
        $requestLog->ip = Yii::$app->request->userIP;
        $requestLog->action = Yii::$app->requestedAction->id;
        $params = [
            'GET params' => Yii::$app->request->get(),
            'POST params' => Yii::$app->request->post(),
        ];
        $requestLog->params = json_encode($params, JSON_THROW_ON_ERROR);

        $requestLog->save();

        return parent::beforeAction($action);
    }
}