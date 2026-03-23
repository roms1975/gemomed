<?php

use yii\db\Migration;

class m260323_111101_create_table_stat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip' => $this->string(15)->notNull(),
            'count' => $this->integer()->defaultValue(0),
        ]);

        $this->createIndex('idx-stat-link_id', 'stat', 'link_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-stat-link_id', 'stat');
        $this->dropTable('stat');
    }

}
