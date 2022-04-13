<?php

namespace wm\admin\controllers\ui\form;

use wm\admin\models\ui\form\Fields;
use wm\admin\models\ui\form\Fieldset;

class FormController extends \wm\admin\controllers\ActiveRestController
{
    public $modelClass = Fieldset::class;

    public function actionFieldsets() {
        $model = Fieldset::find()->where(['id' => 1])->one();
        $res = [];
        if ($model) {
            $res = $model->fields;
        }
        return $res;
    }

}