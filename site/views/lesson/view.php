<?php
use yii\helpers\Url;

$this->title = $lesson->title;
?>
<h1><?= $lesson->title ?></h1>
<div class="body">
    <?= $lesson->content ?>
</div>
<button class="next btn btn-success" data-url='<?= Url::to(['lesson/next'])?>' >Урок просмотрен и освоен</button>


