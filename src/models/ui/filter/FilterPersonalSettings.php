<?php

namespace wm\admin\models\ui\filter;

use wm\yii\db\ActiveRecord;
/**
 * Class FilterPersonalSettings
 * @package wm\admin\models\ui\filter
 */
class FilterPersonalSettings extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter_personal_settings';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['filterId', 'name', 'order', 'fixed'], 'required'],
            [['itemId', 'userId', 'order', 'visible'], 'integer'], [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
            [
                ['filterId'],
                'exist', 'skipOnError' => true,
                'targetClass' => FilterItem::class,
                'targetAttribute' => ['itemId' => 'id']
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filterId' => 'Filter ID',
            'order' => 'Позиция',
            'name' => 'Название фильтра',
            'fixed' => 'Фиксированный',
        ];
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->hasOne(Filter::class, ['id' => 'filteId']);
    }
}
