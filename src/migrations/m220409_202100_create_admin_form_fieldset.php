<?php


class m220409_202100_create_admin_form_fieldset extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_fieldset%}}', [
            'id' => $this->primaryKey(),
            'formId' => $this->integer()->notNull(),
            'order' => $this->integer(),
            'title' => $this->string(255),
            'userId' => $this->integer(),
        ]);

        $this->addForeignKey(
          'formId',
          'admin_form_fieldset',
          'formId',
          'admin_forms',
          'id',
          'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_form_fieldset%}}');

        $this->dropForeignKey(
            'formId',
            'admin_forms'
        );
    }
}