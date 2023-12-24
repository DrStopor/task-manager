<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\modules\api\helpers\ResponseHelper;
use app\modules\api\resource\MessageResource;
use Yii;
use yii\console\Response as ConsoleResponse;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\Response as WebResponse;

class MessageController extends ActiveController
{
    public $modelClass = MessageResource::class;
    private Comment $comment;
    private CreateMessage $createMessage;
    private User $user;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->comment = new Comment();
        $this->createMessage = new CreateMessage();
    }

    /**
     * @inheritDoc
     */
    final public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::class
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }

    /**
     * Дополнительно маршрутизирует запросы (вместо конфига)
     * @return array|WebResponse|ConsoleResponse
     */
    final public function actionRequest(): array|WebResponse|ConsoleResponse
    {
        return match (Yii::$app->request->method) {
            'GET' => $this->actionRequests(),
            'POST' => $this->actionCreateMessage(),
            'PUT' => $this->actionSetComment(),
            default => ResponseHelper::prepareResponse([], 405, '[{-_-}] ZZZzz zz z...')
        };
    }

    /**
     * Отдает все сообщения с возможностью фильтрации по статусу
     * @return WebResponse|ConsoleResponse
     */
    final public function actionRequests(): WebResponse|ConsoleResponse
    {
       $accessRoles = ['admin', 'moderator'];

        if (!in_array($this->user->role->name, $accessRoles, true)) {
            return ResponseHelper::prepareResponse([], 403, 'Доступ запрещен');
        }

        $status = Yii::$app->request->get('status');
        if ($status) {
            $model = MessageResource::getAllMessagesByStatusName($status);
            return ResponseHelper::prepareResponse($model);
        }

        $model = MessageResource::getAllActiveMessages();
        return ResponseHelper::prepareResponse($model);
    }

    /**
     * Предоставление ответа на обращение и постановка в очередь отправки
     * @return WebResponse|ConsoleResponse
     */
    final public function actionSetComment(): WebResponse|ConsoleResponse
    {
        $accessRoles = ['admin', 'moderator'];

        if (!in_array($this->user->role->name, $accessRoles, true)) {
            return ResponseHelper::prepareResponse([], 403, 'Доступ запрещен');
        }

        return $this->comment->replyToRequest();
    }

    /**
     * Создание сообщения
     * @return WebResponse|ConsoleResponse
     */
    final public function actionCreateMessage(): WebResponse|ConsoleResponse
    {
        $accessRoles = ['admin', 'moderator', 'public'];

        if (!in_array($this->user->role->name, $accessRoles, true)) {
            return ResponseHelper::prepareResponse([], 403, 'Доступ запрещен');
        }

        return $this->createMessage->createMessage();
    }

    /**
     * Ответ на несуществующий маршрут
     */
    final public function actionNotFound(): WebResponse|ConsoleResponse
    {
        return ResponseHelper::prepareResponse([], 404, '[{-_-}] ZZZzz zz z...');
    }

    /**
     * Логирование запросов
     * @param $action
     * @return bool
     * @throws \JsonException
     */
    public function beforeAction($action): bool
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

        // set user by token
        $token = Yii::$app->request->headers->get('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $user = User::findIdentityByAccessToken($token);
            if ($user) {
                $this->user = $user;
            }
        }

        return parent::beforeAction($action);
    }
}