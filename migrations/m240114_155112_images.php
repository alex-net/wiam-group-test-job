<?php

use yii\db\Migration;

/**
 * Class m240114_155112_images
 */
class m240114_155112_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('images', [
            'id' => $this->integer()->notNull()->comment('Ключ картинки'),
            'result' => $this->boolean()->comment('Решение по картинке'),
        ]);
        $this->createIndex('images-res-ind', 'images', ['result']);
        $this->addPrimaryKey('images-pk', 'images', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('images');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240114_155112_images cannot be reverted.\n";

        return false;
    }
    */
}
