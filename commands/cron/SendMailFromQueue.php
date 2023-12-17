<?php

namespace app\commands\cron;

use app\models\ErrorLog;
use app\models\MailLog;
use app\models\MailQueue;
use app\models\Message as MessageModel;
use yii\console\Controller;
use yii\db\StaleObjectException;
use yii\swiftmailer\Message;

class SendMailFromQueue extends Controller
{
    // fake send mail
    public function actionIndex()
    {
        $mailQueue = MailQueue::find()
            ->where(['status_id' => 1])
            ->andWhere(['<', 'created_at', date('Y-m-d H:i:s', strtotime('-3 days'))])
            ->all();
        foreach ($mailQueue as $mail) {
            $messageMailQueue = new Message();
            $messageMailQueue->setFrom($mail->from);
            $messageMailQueue->setTo($mail->to);
            $messageMailQueue->setSubject($mail->subject);
            $messageMailQueue->setTextBody($mail->body);
            $resultSend = $messageMailQueue->send();
            $mailLog = new MailLog();
            if ($resultSend) {
                $mailLog->setAttributes($mail->getAttributes());
                $mailLog->save();

                $message = MessageModel::findOne($mail->message_id);
                if ($message) {
                    $message->is_send = true;
                    $message->time_send = date('Y-m-d H:i:s');
                    $message->save();
                }

                try {
                    $mail->delete();
                } catch (StaleObjectException $e) {
                    ErrorLog::log($e->getMessage(), __CLASS__, __METHOD__, __LINE__);
                } catch (\Throwable $e) {
                    ErrorLog::log($e->getMessage(), __CLASS__, __METHOD__, __LINE__);
                }
                continue;
            }

            $mailLog->setAttributes($mail->getAttributes());
            $mailLog->error = "Error send mail. Result send: $resultSend. Error: " . $messageMailQueue->getSwiftMessage()->toString();
            $mailLog->save();
        }

        return 0;
    }
}