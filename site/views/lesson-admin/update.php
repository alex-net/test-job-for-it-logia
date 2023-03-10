<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lesson $model */

$this->title = 'Обновление урока: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Управление уроками', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="lesson-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
