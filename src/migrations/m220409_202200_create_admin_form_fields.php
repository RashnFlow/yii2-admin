<?php


class m220409_202200_create_admin_form_fields extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_fields%}}', [
            'id' => $this->primaryKey(),
            'fieldsetId' => $this->integer()->notNull(),
            'type' => $this->string(20)->notNull(),
            'name' => $this->string(255)->notNull(),
            'label' => $this->string(255)->notNull(),
            'readOnly' => $this->boolean()->notNull(),
            'value' => $this->integer(),
            'fieldParams' => $this->json(),
            'visible' => $this->boolean()->notNull(),
            'order' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fieldsetId',
            'admin_form_fields',
            'fieldsetId',
            'admin_form_fieldset',
            'id',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_form_fields%}}');

        $this->dropForeignKey(
            'fieldsetId',
            'admin_form_fieldset'
        );
    }
}