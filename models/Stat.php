<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stat".
 *
 * @property int $id
 * @property int $link_id
 * @property string $ip
 * @property int|null $count
 */
class Stat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count'], 'default', 'value' => 0],
            [['link_id', 'ip'], 'required'],
            [['link_id', 'count'], 'integer'],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_id' => 'Link ID',
            'ip' => 'Ip',
            'count' => 'Count',
        ];
    }

}
