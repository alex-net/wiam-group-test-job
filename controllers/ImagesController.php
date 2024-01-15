<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Image;

class ImagesController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'verbs' => ['POST'], 'actions' => ['next']],
                    ['allow' => true, 'actions' => ['index']],

                ],
            ]
        ];
    }
    /**
     * Страница просмотра картинок
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'height' => getenv('img_height'),
            'width' => getenv('img_width'),
        ]);
    }

    /**
     * Запрос следующей картинки
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionNext()
    {
        $post = $this->request->post();
        if (!empty($post['imgId'])) {
            $img = new Image([
                'id' => intval($post['imgId']),
                'result' => isset($post['action']) && $post['action'] == 'yes',
            ]);
            $img->saveResult();
        }
        $img = Image::newImage();
        return $this->asJson([
            'width' => $img->width,
            'height' => $img->height,
            'src' => $img->src,
            'id' => $img->id,
        ]);
    }
}