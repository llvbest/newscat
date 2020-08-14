<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rest\Controller as ApiBaseController;
use frontend\models\Rubric;
use frontend\models\News;

/**
 * Api controller
 */
class ApiController extends ApiBaseController
{
    public $modelClass = 'frontend\models\Rubric';
    
    public function actionView($id, $page = 1)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $news = [];
        $rubric_id = [$id];
        $rubric = Rubric::findOne($id);
        
        if(!empty($rubric)){
            /** get leaves rubric and merge with main rubric news */
            $rubric_leaves = $rubric->leaves()->all();
            foreach ($rubric_leaves as $leave) {
                $rubric_id[] = $leave->id;
            }
            
            /** get all news with main and children rubric */
            $news = News::find()
            ->select('news.*')
            ->innerJoin('news2rubric', '`news2rubric`.`id_news` = `news`.`id`')
            ->where(['in', 'news2rubric.id_rubric', $rubric_id])
            //->with('rubrics')
            //->limit(5)
            //->offset(5)
            ->all();
        }
        
        return $news;
    }
}
