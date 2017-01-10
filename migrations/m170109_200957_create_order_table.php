<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m170109_200957_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'sender_name' => $this->string(15)->notNull(),
            'recipient_name' => $this->string(15)->notNull(),
            'count' => $this->money()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'process_time' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `sender_name`
        $this->createIndex(
            'idx-order-sender_name',
            'order',
            'sender_name'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-sender_name',
            'order',
            'sender_name',
            'user',
            'nickname',
            'CASCADE'
        );

        // creates index for column `recipient_name`
        $this->createIndex(
            'idx-order-recipient_name',
            'order',
            'recipient_name'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-recipient_name',
            'order',
            'recipient_name',
            'user',
            'nickname',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-order-sender_name',
            'order'
        );

        // drops index for column `sender_name`
        $this->dropIndex(
            'idx-order-sender_name',
            'order'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-order-recipient_name',
            'order'
        );

        // drops index for column `recipient_name`
        $this->dropIndex(
            'idx-order-recipient_name',
            'order'
        );

        $this->dropTable('order');
    }
}
