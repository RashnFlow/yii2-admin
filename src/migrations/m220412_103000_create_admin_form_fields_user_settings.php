<?php


class m220412_103000_create_admin_form_fields_user_settings extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_fields_user_settings%}}', [
            'id' => $this->primaryKey(),
            'fieldId' => $this->integer()->notNull(),
            'visible' => $this->boolean()->notNull(),
            'order' => $this->integer()->notNull(),
            'userFieldsetId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fieldId',
            'admin_form_fields_user_settings',
            'fieldId',
            'admin_form_fields',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'userFieldsetId',
            'admin_form_fields_user_settings',
            'userFieldsetId',
            'admin_form_fieldset',
            'id',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_form_fields_user_settings%}}');

        $this->dropForeignKey(
            'fieldId',
            'admin_form_fields'
        );

        $this->dropForeignKey(
            'fieldsetId',
            'admin_form_fieldset'
        );
    }
}