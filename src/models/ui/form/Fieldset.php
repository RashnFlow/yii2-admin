<?php

namespace wm\admin\models\ui\form;

/**
 * This is the model class for table "admin_form_fieldset".
 *
 * @property integer $id
 * @property integer $formId
 * @property string $title
 * @property integer $order
 */
class Fieldset extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_form_fieldset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'formId'
            ], 'required'],
            [['id' , 'formId', 'order'], 'integer'],
            [['id'], 'unique'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function getForm()
    {
        return $this->hasOne(Forms::class, ['id' => 'formId']);
    }

    public function getFields()
    {
        return $this->hasMany(Fields::class, ['fieldsetId' => 'id']);
    }

    public function getFieldsUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['userFieldsetId' => 'id']);
    }
}