<?php

namespace wm\admin\models\ui\form;

/**
 * This is the model class for table "admin_form_fields".
 *
 * @property integer $id
 * @property string $mode
 * @property string $title
 * @property boolean $canToggleMode
 */
class Forms extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_forms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'mode',
                'title',
                'canToggleMode'
            ], 'required'],
            [['id'], 'integer'],
            [['id'], 'unique'],
            [['mode',], 'string', 'max' => 5],
            [['title',], 'string', 'max' => 255],
            [['canToggleMode'], 'boolean'],
        ];
    }

    public function getFieldset()
    {
        return $this->hasMany(Fieldset::class, ['formId' => 'id']);
    }

    public function getFields()
    {
        return $this->hasMany(Fields::class, ['fieldsetId' => 'id'])
            ->via('fieldset');
    }

    public function getFieldsUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['userFieldsetId' => 'id'])
            ->via('fieldset');
    }

}