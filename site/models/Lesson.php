<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use Yii;

class Lesson extends ActiveRecord
{
    public static function tableName()
    {
        return 'lessons';
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'weight' => 'Вес',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'trim'],
            [['title', 'content'], 'required'],
            ['title', 'string', 'max' => 50],
            ['weight', 'integer'],
            ['weight', 'default', 'value' => 0],
        ];
    }

    /**
     * список уроков на главной странице
     */
    public static function list(?int $lid = null)
    {
        $list = static::find(); //->leftJoin(['u' => '{{%users_less}}'], 'u.lid = l.id and u.uid = :user', [':user' => $uid] );

        $list->orderBy(['weight' => SORT_ASC]);
        $list = $list->asArray()->all();

        $passed = intval(isset($lid));
        foreach ($list as $x => $y) {
            $list[$x]['passed'] = $passed;
            if ($y['id'] == $lid || !$passed) {
                $passed--;
            }
        }

        return new ArrayDataProvider([
            'allModels' => $list,
            'pagination' => false,
        ]);
    }

    /**
     * Определить номер следующего урока ... для пользователя uid
     * @param $uid  string  Номер пользователя ..
     */
    public static function nextLess($uid)
    {
        $lastLid = Yii::$app->db->createCommand('select lid from {{%users_less}} where uid=:id', [':id' => $uid])->queryScalar();
        // не пройдено ни одного урока ..
        if ($lastLid === false) {
            return Yii::$app->db->createCommand('select id from {{%lessons}} order by weight asc limit 1')->queryScalar();
        }
        $list = Yii::$app->db->createCommand('select id, lead(id) over(order by weight asc) as next from {{%lessons}}  order by weight asc')->queryAll();
        Yii::info($list, '$list');
        foreach ($list as $l) {
            if ($l['id'] == $lastLid) {
                return $l['next'] ?? 'finish';
            }
        }

    }

    /**
     * пройден ли конкретный урок
     */
    public function getIsPassed($uid)
    {
        $lastPassed = Yii::$app->db->createCommand('select lid from {{%users_less}} where uid = :uid', [':uid' => $uid])->queryScalar();
        // не пройдено ни оного урока
        if (empty($lastPassed))
            return false;

        $list = static::list()->models;
        $passed = true;
        foreach ($list as $l) {
            if ($l['id'] == $this->id) {
                return $passed;
            }
            if ($l['id'] == $lastPassed) {
                $passed = false;
            }
        }


        // запросить последний пройденный урок юзеря..


        Yii::info($lastPassed, '$lastPassed');
        return false;
    }

    /**
     * добавление обновление записи последнего пройденного урока ...
     *
     * @param      <type>  $uid    The uid
     */
    public function setAsPassed($uid)
    {
        Yii::$app->db->createCommand()->upsert('{{%users_less}}', ['uid' => $uid, 'lid' => $this->id], ['lid' => $this->id])->execute();
    }
}