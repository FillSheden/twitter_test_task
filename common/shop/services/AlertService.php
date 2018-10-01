<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 21:19
 */

namespace common\shop\services;

use Yii;

class AlertService
{
    public static function printSessionMessage($key)
    {
        if (Yii::$app->session->has($key)) {
            echo '<div class="card" style="background-color:#80bdff"><h5 style="text-align: center">';
            echo Yii::$app->session->getFlash($key);
            echo '</h5></div>';
            Yii::$app->session->remove($key);
        }
    }
}