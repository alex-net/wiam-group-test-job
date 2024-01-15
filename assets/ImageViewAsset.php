<?php

namespace app\assets;

use yii\web\AssetBundle;
use app\assets\AppAsset;

class ImageViewAsset extends AssetBundle
{
    public $sourcePath = '@app/front/js';

    public $js = ['image-view.js'];

    public $depends = [
        AppAsset::class,
    ];

}