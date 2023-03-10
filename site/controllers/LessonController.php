<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;
use yii\helpers\Url;

use app\models\Lesson;

class LessonController extends Controller
{
    /**
     * Список уроков ..
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionIndex()
    {
        $allLesson = Lesson::list(Yii::$app->user->identity->lastLesson);
        return $this->render('index', [
            'list' => $allLesson,
        ]);
    }

    /**
     * Просмотр урока
     *
     * @param      <type>  $lid    The lid
     */
    public function actionView($lid)
    {
        $l = Lesson::findOne($lid);
        if (!$l) {
            throw new \yii\web\NotFoundHttpException('Урок не найден');
        }
        $next = Lesson::nextLess(Yii::$app->user->id);
        if (!$l->getIsPassed(Yii::$app->user->id) && $next != $l->id) {
            return $this->redirect(['lesson/view', 'lid' => $next]);
        }

        return $this->render('view', [
            'lesson' => $l,
        ]);
    }

    /**
     * вернуть ссылку на следующий урок
     *
     */
    public function actionNext()
    {
        $this->response->format = Response::FORMAT_JSON;
        $fromLesson = parse_url(Yii::$app->request->referrer, PHP_URL_PATH);
        if (preg_match('#/lesson/(\d+)$#', $fromLesson, $lid)) {
            $l = Lesson::findOne(end($lid));
            if (!$l) {
                return $this->asJson(['error' => 'неизветсный урок']);
            }
            $l->setAsPassed(Yii::$app->user->id);

            $lid = end($lid);

            Yii::info($lid, '$lid');
        }

        $nextLess = Lesson::nextLess(Yii::$app->user->id);
        Yii::info($nextLess, 'next');
        if ($nextLess == 'finish') {
            Yii::$app->session->addFlash('success', 'Все уроки пройдёны успешно');
            return $this->asJson(['next' => Url::to(['lesson/index'])]);
        }

        return $this->asJson(['next' => Url::to(['lesson/view', 'lid' => $nextLess])]);
    }
}