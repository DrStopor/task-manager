<?php

namespace app\modules\api\controllers;

use app\modules\api\helpers\Helper;
use app\modules\api\helpers\ResponseHelper;
use app\modules\api\resource\MailQueueResource;
use app\modules\api\resource\MessageResource;
use app\modules\api\resource\StatusResource;
use Yii;
use yii\web\Response;

class Comment
{

    final public function replyToRequest(): Response|\yii\console\Response
    {
        $id = Yii::$app->request->get('id');
        $comment = Yii::$app->request->post('comment');
        $statusName = Yii::$app->request->post('status');
        $userId = Yii::$app->user->id;

        if (!$id || !$comment || !$statusName || !$userId) {
            return ResponseHelper::prepareResponse([], 400, 'отсутствуют обязательные поля');
        }

        $status = StatusResource::getStatusByName($statusName);
        if (!$status) {
            return ResponseHelper::prepareResponse([], 400, 'не удалось сохранить. статус не найден');
        }

        $message = MessageResource::findOne($id);
        if (!$message) {
            return ResponseHelper::prepareResponse([], 404, 'не удалось сохранить. сообщение не найдено');
        }
        if ($message->status_id === $status->id) {
            return ResponseHelper::prepareResponse(['success' => true, 'message' => 'статус не изменился'], 204);
        }

        $message->comment = Helper::getClearedString($comment);
        $message->status_id = $status->id;
        $message->user_id = $userId;
        $message->updated_at = date('Y-m-d H:i:s');
        if ($message->save()) {
            $mailQueue = new MailQueueResource();
            $mailQueue->setFieldsFromMessage($message);
            $mailQueue->status = $status->id;

            if ($mailQueue->isDuplicate()) {
                return ResponseHelper::prepareResponse(
                    ['success' => true, 'message' => 'Ответ сохранен. Письмо уже находится в очереди.'],
                    201
                );
            }

            if ($mailQueue->save() === false) {
                return ResponseHelper::prepareResponse([], 400, 'Ответ сохранен, но письмо не добавлено в очередь');
            }

            return ResponseHelper::prepareResponse(
                ['success' => true, 'message' => 'Ответ сохранен. Письмо добавлено в очередь на отправку.'],
                201
            );
        }

        return ResponseHelper::prepareResponse([], 400, 'не удалось сохранить. ошибка при сохранении');
    }
}