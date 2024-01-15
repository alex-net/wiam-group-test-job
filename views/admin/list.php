<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\Image;

$this->title = 'Результаты по картинкам';

echo GridView::widget([
    'dataProvider' => $dp,
    'columns' => [
        'id:text:Идентификатор',
        'result:boolean:Одобрено',
        [
            'class' => ActionColumn::class,
            'template' => '{view} {delete}',
            'urlCreator' => function ($act, $model, $key, $ind, $col) {
                Yii::info($act, '$col');
                Yii::info($model, 'model');
                if ($act == 'view') {
                    return Image::newImage($model['id'])->url;
                }
                return Url::to([$act, 'id' => $model['id']]);
            }
        ],
    ],
]);