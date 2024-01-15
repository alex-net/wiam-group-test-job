<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use app\models\Image;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                ]],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('list', [
            'dp' => Image::listResults(),
        ]);
    }

    public function actionDelete($id)
    {
        if (Image::dropResult($id)) {
            Yii::$app->session->addFlash('info', "Результат для картинки $id удалён");
        }
        return $this->redirect(['index']);
    }
}