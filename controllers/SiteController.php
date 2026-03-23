<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Links;

class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Links();

        if (Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate(['url'])) {
                    $existingLink = Links::findOne(['url' => $model->url]);
                    if ($existingLink) {
                        return $this->renderPartial('index', ['model' => $existingLink]);
                    }
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Ссылка сохранена.');
                    return $this->renderPartial('index', [
                        'model' => $model,
                    ]);
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка!');
                }
            }
        }

        return $this->render('index', ['model' => $model]);
    }
}
