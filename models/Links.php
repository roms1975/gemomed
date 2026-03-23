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

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->short_url)) {
                $this->short_url = $this->generateUniqueShortUrl();
            }
            return true;
        }
        return false;
    }

    private function generateUniqueShortUrl($length = 6)
    {
        $domain = Yii::$app->request->hostInfo;
        $code = $domain .'/' . \Yii::$app->security->generateRandomString($length);

        while (static::find()->where(['short_url' => $code])->exists()) {
            $code = $domain .'/' . \Yii::$app->security->generateRandomString($length);
        }

        return $code;
    }

    public function checkAvailability($attribute, $params) {
        error_log(print_r($attribute, true), 3, '/var/www/html/runtime/logs/roms.log');
        error_log(print_r($params, true), 3, '/var/www/html/runtime/logs/roms.log');

        
        $ch = curl_init($this->$attribute);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 400) {
            $this->addError($attribute, 'Данный URL не доступен');
        }
    }

}
