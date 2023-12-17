<?php

namespace app\modules\api\helpers;

use app\models\Message;
use app\models\ResponseLog;
use app\modules\api\resource\MessageResource;
use Yii;
use yii\web\Response;

class ResponseHelper
{

    public string $modelClass = MessageResource::class;

    /**
     * @param array|Message $data
     * @param int $code
     * @param string|null $error
     * @return Response|\yii\console\Response
     */
    final public static function prepareResponse(array|Message $data, int $code = 200, string $error = null): Response|\yii\console\Response
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
        $response->statusCode = $code;
        if ($error) {
            $response->data['error'] = $error;
        }

        $responseLog = new ResponseLog();
        try {
            $responseLog->response = !empty($data)
                ? json_encode($data, JSON_THROW_ON_ERROR)
                : json_encode(['error' => $error], JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            try {
                $responseLog->response = json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $responseLog->response = 'Цепочка ошибок при формировании ответа';
            }
        }
        $responseLog->code = $code;
        $responseLog->user_id = Yii::$app->user->id ?? null;
        $responseLog->message_id = $data->id ?? null;
        $responseLog->save();

        return $response;
    }
}