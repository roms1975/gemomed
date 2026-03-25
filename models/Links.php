<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "links".
 *
 * @property int $id
 * @property string $url
 * @property string $short_url
 */
class Links extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url', 'short_url'], 'string', 'max' => 255],
            ['url', 'url',
                'defaultScheme' => 'https',
                'validSchemes' => ['http', 'https'],
                'message' => 'Введите корректный адрес ссылки с указанием протокола.',
            ],
            ['url', 'checkAvailability']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Реальный Url',
            'short_url' => 'Короткая ссылка',
        ];
    }

    private function generateUniqueShortUrl($length = 6)
    {
        $code = \Yii::$app->security->generateRandomString($length);

        while (static::find()->where(['short_url' => $code])->exists()) {
            $code = \Yii::$app->security->generateRandomString($length);
        }

        return $code;
    }

    public function getFullShortUrl() {
        if (empty($this->short_url))
            return false;

        $domain = Yii::$app->request->hostInfo;
        $controller = Yii::$app->params['forwardController'];
        return $domain .'/' . $controller . '/' . $this->short_url;
    }

    public function checkAvailability($attribute, $params) {
        Yii::info($params, 'user_actions');

        $ch = curl_init($this->$attribute);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 400) {
            $this->addError($attribute, 'Данный URL не доступен');
        } else {
            if (empty($this->short_url)) {
                $this->short_url = $this->generateUniqueShortUrl();
            }
        }
    }

}
