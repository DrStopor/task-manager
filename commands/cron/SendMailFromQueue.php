<?php

namespace app\commands\cron;

use app\models\ErrorLog;
use app\models\MailLog;
use app\models\MailQueue;
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
            $message = new Message();
            $message->setFrom($mail->from);
            $message->setTo($mail->to);
            $message->setSubject($mail->subject);
            $message->setTextBody($mail->body);
            $resultSend = $message->send();
            $mailLog = new MailLog();
            if ($resultSend) {
                $mailLog->setAttributes($mail->getAttributes());
                $mailLog->save();
                try {
                    $mail->delete();
                } catch (StaleObjectException $e) {
                    ErrorLog::log($e->getMessage(), __CLASS__, __METHOD__, __LINE__);
                } catch (\Throwable $e) {
                    ErrorLog::log($e->getMessage(), __CLASS__, __METHOD__, __LINE__);
                }
            }

            $mailLog->setAttributes($mail->getAttributes());
            $mailLog->error = "Error send mail. Result send: $resultSend. Error: " . $message->getSwiftMessage()->toString();
            $mailLog->save();
        }

        return 0;
    }
}