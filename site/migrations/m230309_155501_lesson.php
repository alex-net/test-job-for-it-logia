<?php

use yii\db\Migration;

/**
 * Class m230309_155501_kurs
 */
class m230309_155501_lesson extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lessons}}', [
            'id' => $this->primaryKey()->notNull()->comment('Ключик урока'),
            'title' => $this->string(50)->notNull()->comment('Заголовок урока'),
            'content' => $this->text()->comment('Текст урока'),
            'weight' => $this->integer()->notNull()->defaultValue(0)->comment('Вес урока'),
        ]);
        $this->createIndex('lesss_weight_ind', '{{%lessons}}', ['weight']);
        $this->createIndex('less_title_ind', '{{%lessons}}', ['title']);
        $this->createTable('{{%users_less}}', [
            'uid' => $this->integer()->comment('сылка на пользователя'),
            'lid' => $this->integer()->comment('последний пройденный урок'),
        ]);
        $this->addPrimaryKey('uk_pk', '{{%users_less}}', ['uid']);
        $this->addForeignKey('user_less_fk', '{{%users_less}}', ['lid'], '{{%lessons}}', ['id'], 'cascade', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_less}}');
        $this->dropTable('{{%lessons}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230309_155501_kurs cannot be reverted.\n";

        return false;
    }
    */
}
