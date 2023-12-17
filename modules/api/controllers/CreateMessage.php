<?php

namespace app\modules\api\controllers;

use app\modules\api\helpers\ResponseHelper;
use app\modules\api\resource\ContactResource;
use app\modules\api\resource\MessageResource;
use Yii;
use yii\console\Response as ConsoleResponse;
use yii\validators\EmailValidator;
use yii\web\Response as WebResponse;

class CreateMessage
{
    final public function createMessage(): WebResponse|ConsoleResponse
    {
        $extId = Yii::$app->request->post('id');
        $author = Yii::$app->request->post('name');
        $email = Yii::$app->request->post('email');
        $message = Yii::$app->request->post('message');

        $emailValidator = new EmailValidator();
        if (!$emailValidator->validate($email)) {
            return ResponseHelper::prepareResponse([], 400, 'Некорректный email');
        }

        $contact = ContactResource::findOne(['email' => $email]);
        if (!$contact) {
            $contact = new ContactResource();
            $contact->name = $author;
            $contact->email = $email;
            if (!$contact->save()) {
                return ResponseHelper::prepareResponse([], 400, 'Не удалось сохранить контакт');
            }
        }

        $messageModel = new MessageResource();
        $messageModel->contact_id = $contact->id;
        $messageModel->message = $message;
        $messageModel->ext_id = $extId;

        if (!$messageModel->validate()) {
            return ResponseHelper::prepareResponse([], 400, 'Не удалось сохранить сообщение');
        }

        if ($messageModel->save()) {
            return ResponseHelper::prepareResponse($messageModel, 201);
        }
        return ResponseHelper::prepareResponse([], 400, 'Не удалось сохранить сообщение');
    }
}