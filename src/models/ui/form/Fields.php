<?php

namespace wm\admin\models\ui\form;

/**
 * This is the model class for table "admin_form_fields".
 *
 * @property integer $id
 * @property integer $fieldsetId
 * @property string $type
 * @property string $name
 * @property string $label
 * @property integer $order
 * @property integer $value
 * @property boolean $readOnly
 * @property boolean $visible
 */
class Fields extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_form_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'fieldsetId',
                'type',
                'name',
                'label',
                'visible',
                'order'
            ], 'required'],
            [['id' , 'fieldsetId', 'order', 'value'], 'integer'],
            [['id'], 'unique'],
            [['type',], 'string', 'max' => 20],
            [['name', 'label'], 'string', 'max' => 255],
            [['readOnly', 'visible'], 'boolean'],
        ];
    }

    public function getFieldset()
    {
        return $this->hasOne(Fieldset::class, ['id' => 'fieldsetId']);
    }

    public function getForm()
    {
        return $this->hasOne(Forms::class, ['id' => 'formId'])
            ->via('fieldset');
    }

    public function getFieldsUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['fieldId' => 'id']);
    }
}