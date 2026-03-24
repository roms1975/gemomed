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
        $link = Links::findOne(['short_url' => \Yii::$app->request->getAbsoluteUrl()]);

        Yii::$app->db->createCommand()->upsert('stat', [
            'link_id' => $link->id,
            'ip' => $ip,
            'count' => 1,
        ], [
            'count' => new \yii\db\Expression('count + 1'),
        ], false)->execute();

        return $this->redirect($link->url);
    }
}
