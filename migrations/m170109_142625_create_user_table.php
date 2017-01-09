<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170109_142625_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'nickname' => $this->string(15)->notNull()->unique(),
            'authKey' => $this->string(64)->notNull()->unique(),
            'accessToken' => $this->string(64)->notNull()->unique(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
