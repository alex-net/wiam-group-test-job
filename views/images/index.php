<?php

use app\assets\ImageViewAsset;

$this->title = 'Просмотр картинок';

ImageViewAsset::register($this);
?>
<h1>Картинка #<span class="image-id"></span></h1>
<div class="container text-center">
    <div class="row">
        <div class="col-12">
            <img class="img"  height="<?= $height ?>" width="<?= $width ?>" />
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-6 id-su"><span data-action='no' class="btn btn-danger control-buttons">Отклонить</span></div>
        <div class="col-6 id-err visually-hidden"><span data-action='repeat' class="btn id-err btn-info control-buttons">Повторить запрос</span></div>
        <div class="col-6 id-su"><span data-action='yes' class="btn btn-primary control-buttons">Одобрить</span></div>
    </div>
</div>
