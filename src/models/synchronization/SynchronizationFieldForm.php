<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\Synchronization;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "admin_synchronization_field".
 *
 * @property string $name
 * @property int $synchronizationEntityId
 * @property string $title
 *
 * @property Synchronization $synchronizationEntity
 */
class SynchronizationFieldForm extends Model
{
    public $name;
    public $synchronizationEntityId;


    public function rules()
    {
        return [
            [['name', 'synchronizationEntityId'], 'required'],
            [['synchronizationEntityId'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typeId' => 'Тип поля',
            'name' => 'Системное имя',
            'synchronizationEntityId' => 'Идентификатор сущности',
            'title' => 'Название',
        ];
    }
    
    public function addField(){
        $model = new SynchronizationField();
        $model->name = $this->name;
        $model->synchronizationEntityId = $this->synchronizationEntityId;

        $modelSync = Synchronization::find()->where(['id' => $this->synchronizationEntityId])->one();
        $fields = $modelSync->getB24Fields();
        $field = ArrayHelper::getValue($fields, $this->name);
        $model->title = ArrayHelper::getValue($field, 'formLabel')?:ArrayHelper::getValue($field, 'title');
        $model->typeId = 1; //TODO
        $model->save();
        if($model->errors){
            Yii::error($model->errors, 'addField $model->errors');
            $this->addError('name', 'Ошибка!');
            return false;
        }
        return true;
    }

    
}