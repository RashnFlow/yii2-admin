<?php


namespace wm\admin\models\ui\form;


class UserSettings extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_form_fields_user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'fieldId',
                'visible',
                'order',
                'userFieldsetId',
                'userId'
            ], 'required'],
            [[
                'id' ,
                'fieldId',
                'order',
                'userFieldsetId',
                'userId'], 'integer'],
            [['id'], 'unique'],
            [['visible'], 'boolean'],
        ];
    }

    public function getFields()
    {
        return $this->hasOne(Fields::class, ['id' => 'fieldId']);
    }

    public function getFieldset()
    {
        return $this->hasOne(Fieldset::class, ['id' => 'userFieldsetId']);
    }

    public function getForm()
    {
        return $this->hasOne(Forms::class, ['id' => 'formId'])
            ->via('fieldset');
    }
}