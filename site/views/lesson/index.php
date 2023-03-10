<?php

use yii\widgets\ListView;
use yii\helpers\Html;

$this->title = 'Уроки';
yii::info($list->models, 'model');
echo Html::beginTag('ul', ['class' => "lessons-for-user"]);
foreach ($list->models as $l) {
    $opt = ['data-id' => $l['id']];
    $text = $l['title'];
    if (isset($l['passed'])) {
        switch (true) {
            case $l['passed'] > 0:
                $opt['class'][] = 'passed';
                break;
            case empty($l['passed']):
                $opt['class'][] = 'next';
                $text = Html::a($text, ['lesson/view', 'lid' => $l['id']]);
                break;
        }
    }

    echo Html::tag('li', $text, $opt);
}
echo Html::endTag('ul');

?>



