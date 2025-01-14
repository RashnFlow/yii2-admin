<?php

namespace wm\admin\models\ui\filter;

use Yii;
use wm\yii\db\ActiveRecord;

/**
 * This is the model class for table "menuitem".
 *
 * @property int $id
 * @property string $title
 * @property int $visible
 * @property int $position
 * @property int $userId
 * @property string $url
 * @property int $menuId
 * @property Menu $menu
 */
class FilterItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filters_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemName', 'visible', 'order', 'value', 'filterId'], 'required'],
            [['visible', 'order', 'filterId'], 'integer'],
            [['itemName', 'value'], 'string', 'max' => 255],
            [
                ['filterId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Filter::class,
                'targetAttribute' => ['filterId' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'itemName' => 'Название фильтра ',
            'visible' => 'Скрытость',
            'order' => 'Позиция',
            'value' => 'Значение',
            'filterId' => 'Filter ID',
        ];
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(Filter::class, ['id' => 'filterId']);
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id', 'itemName', 'visible', 'order', 'value', 'filterId',
            'filter' => function () {
                $res = json_decode($this->filter);
                return $res;
            },
        ];
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        $attributeLabels = $this->attributeLabels();
        unset($attributeLabels['filterId']);
        $attributeLabels['filter'] = 'фильтр';
        return $this->convertShema($attributeLabels);
    }

    /**
     * @param $filterId
     * @param $userId
     * @return array
     */
    public static function getItems($filterId, $userId)
    {
        if (!Filters::find()->where(['id' => $filterId])->one()) {
            Yii::error('$filterId не содержится в Базе данных');
            return [];
        }
        $models = self::find()
            ->where(['filterId' => $filterId])
//            ->joinWith(
//                ['filterItemPersonalSettings' => function($q) use($userId){
//                    $q->andWhere(['userId' => $userId]);
//                }]
//            )
            ->all();
        $res = [];
        foreach ($models as $value) {
            $settings = $value->getFilterItemPersonalSettings()->where(['userId' => $userId])->one();
            if ($settings) {
                $value->order = $settings->order;
                $value->visible = $settings->visible;
            }
            $res[] = $value;
        }
        return $res;
    }

    /**
     * Gets query for [[MenuItemPersonals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterItemPersonalSettings()
    {
        return $this->hasMany(FilterItemPersonalSettings::class, ['itemId' => 'id']);
    }
}
