<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\gii\ColumnSchema;
use wm\yii\db\ActiveRecord;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class BaseEntity extends ActiveRecord
{
    public static $primaryKeyColumnName = 'id';

    public static $synchronizationFullListJob = '';

    public static $synchronizationDeltaJob = '';

    public static $synchronizationFullGetJob = '';

    public static function createColumns(array $addFieldNames)
    {
        $fields = static::getB24Fields();
        $table = Yii::$app->db->getTableSchema(static::tableName());
        foreach ($fields as $fieldName => $field) {
            if (!isset($table->columns[$fieldName]) && in_array($fieldName, $addFieldNames)) {
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addColumn(
                        $table->name,
                        $fieldName,
                        ColumnSchema::getDbType($field)
                    )
                    ->execute();
            }
        }
        return true;
    }

    public static function deleteColumn($fieldName)
    {
        $table = Yii::$app->db->getTableSchema(static::tableName());
        Yii::warning(ArrayHelper::toArray($table->columns), 38);
        if (ArrayHelper::getValue($table, 'columns.' . $fieldName)) {
            Yii::warning(40);
            $res = Yii::$app
                ->db
                ->createCommand()
                ->dropColumn(
                    $table->name,
                    $fieldName
                )
                ->execute();
            Yii::warning($res, '$res');
        }
        return true;
    }


    public function rules()
    {
        return [[$this->attributes(), 'safe']];
    }

    public static function getCountDb()
    {
        if (!Yii::$app->db->getTableSchema(static::tableName())) {
            return null;
        }
        return static::find()->count();
    }

    public static function createTable($synchronizationEntityId)
    {
        Yii::$app
            ->db
            ->createCommand()
            ->createTable(
                static::tableName(),
                [static::$primaryKeyColumnName => Schema::TYPE_INTEGER,]
            )
            ->execute();
        Yii::$app
            ->db
            ->createCommand()
            ->addPrimaryKey(
                static::$primaryKeyColumnName,
                static::tableName(),
                static::$primaryKeyColumnName
            )
            ->execute();
        Yii::$app
            ->db
            ->createCommand()
            ->insert(
                'admin_synchronization_field',
                [
                    'name' => static::$primaryKeyColumnName,
                    'synchronizationEntityId' => $synchronizationEntityId,
                    'title' => static::$primaryKeyColumnName,
                    'noDelete' => 1
                ]
            )
            ->execute();
    }

    public static function addJobFull($method, $dateTimeStart = null)
    {
        $delay = 0;
        if ($dateTimeStart) {
            $diff = strtotime($dateTimeStart) - time();
            if ($diff > 0) {
                $delay = $diff;
            }
        }

        $objFullSync = null;

        switch ($method) {
            case 'list':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class
                    ]
                );
                break;
            case 'get':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullGetJob,
                        'modelClass' => static::class
                    ]
                );
                break;
            default:
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class
                    ]
                );
        }


        $id = Yii::$app->queue->delay($delay)->ttr(3600)->push($objFullSync);
        return $id;
    }

    public static function synchronization()
    {
        $id = Yii::$app->queue->push(
            Yii::createObject(
                [
                    'class' => static::$synchronizationDeltaJob,
                    'modelClass' => static::class
                ]
            )
        );

        return $id;
    }

    public static function getB24Fields()
    {
        return [];
    }
}
