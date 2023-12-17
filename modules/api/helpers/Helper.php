<?php

namespace app\modules\api\helpers;

class Helper extends \yii\helpers\BaseStringHelper
{
    public static function getClearedString(string $string): string
    {
        return addslashes(
            htmlspecialchars(
                strip_tags($string)
            )
        );
    }
}