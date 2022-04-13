<?php


class m220409_202000_create_admin_forms extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_forms%}}', [
            'id' => $this->primaryKey(),
            'mode' => $this->string(5)->notNull(),
            'title' => $this->string(255)->notNull(),
            'canToggleMode' => $this->boolean()->notNull(),
            'action' => $this->json(),
            'params' => $this->json(),
            'buttons' => $this->json(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_forms%}}');
    }
}