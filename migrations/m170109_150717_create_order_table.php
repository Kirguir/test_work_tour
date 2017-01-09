<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m170109_150717_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'count' => $this->money()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'process_time' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `sender_id`
        $this->createIndex(
            'idx-order-sender_id',
            'order',
            'sender_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-sender_id',
            'order',
            'sender_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `recipient_id`
        $this->createIndex(
            'idx-order-recipient_id',
            'order',
            'recipient_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-recipient_id',
            'order',
            'recipient_id',
            'user',
            'id',
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
            'fk-order-sender_id',
            'order'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            'idx-order-sender_id',
            'order'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-order-recipient_id',
            'order'
        );

        // drops index for column `recipient_id`
        $this->dropIndex(
            'idx-order-recipient_id',
            'order'
        );

        $this->dropTable('order');
    }
}
