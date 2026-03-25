<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Links;
use app\models\Stat;

class ForwardController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($link)
    {
        $ip = Yii::$app->request->userIP;
        //$url = \Yii::$app->request->getAbsoluteUrl();
        $model = Links::findOne(['short_url' => $link]);

        $stat = Stat::findOne(['link_id' => $model->id, 'ip' => $ip]);

        if ($stat) {
            $stat->updateCounters(['count' => 1]);
        } else {
            $stat = new Stat();
            $stat->link_id = $model->id;
            $stat->ip = $ip;
            $stat->count = 1;
            $stat->save();
        }

        return $this->redirect($model->url);
    }
}
