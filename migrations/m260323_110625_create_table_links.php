<?php

use yii\db\Migration;

class m260323_110625_create_table_links extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('links', [
            'id' => $this->primaryKey(),
            'url' => $this->string(255)->notNull()->unique(),
            'short_url' => $this->string(255)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('links');
    }

}
