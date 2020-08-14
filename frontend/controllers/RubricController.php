<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Rubric;
use frontend\models\RubricSearch;
use frontend\models\News;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use frontend\models\News;

/**
 * RubricController implements the CRUD actions for Rubric model.
 */
class RubricController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Rubric models.
     * @return mixed
     */
    public function actionIndex()
    {
        $news = [];
        $rubric_id = [2];
        $rubric = Rubric::findOne(2);
        
        if(!empty($rubric)){
            /** get news main rubric */
            $news = $rubric->news;
            
            /** get leaves rubric and merge with main rubric news */
            $rubric_leaves = $rubric->leaves()->all();
            foreach ($rubric_leaves as $leave) {
               $rubric_id[] = $leave->id;
            }
        }
        //echo '<pre>'.print_r($rubric_id,true).'</pre>';
        //Yii::$app->request->get('page')
        
        /*
        $news = News::find()
            ->with(//'rubrics'
                
                [
                    'rubrics' => function ($query) {
                        $query->andWhere(['in', 'id', [2, 5]]);
                    },
                ]
            )
            ->limit(5)
            //->offset(5)
            ->all();
        */
        $news = News::find()
    ->select('news.*')
    ->innerJoin('news2rubric', '`news2rubric`.`id_news` = `news`.`id`')
    ->where(['in', 'news2rubric.id_rubric', $rubric_id])
            //->with('rubrics')
            //->limit(5)
            //->offset(5)
            //->orderBy('id ASC')
            ->all();
    
        //echo '<pre>'.print_r($news,true).'</pre>';
        foreach ($news as $news_one) {
	       echo '<pre>'.print_r($news_one->title,true).'</pre>'.$news_one->rubrics->name;
           foreach ($news_one->rubrics as $rubric_one) {
                echo '$news_one->rubrics->name:'.$rubric_one->name.'<br />';
           }
        }
        
        exit();
/*
$roots = Rubric::findOne(2);
$news = [];
$news = $roots->news;

$rubric_leaves = $roots->leaves()->all();

        foreach ($rubric_leaves as $leave) {
	       echo '<pre>'.print_r($leave->name,true).'</pre>';
           $news = array_merge($news, $leave->news);
        }
        
        echo 'news<br />';
        foreach ($news as $news_one) {
	       echo '<pre>'.print_r($news_one->title,true).'</pre>';
        }
        exit();

        foreach ($news as $news_one) {
	       echo '<pre>'.print_r($news_one->title,true).'</pre>';
        }

echo '<pre>'.print_r($roots->name,true).'</pre>';
        exit();
        
$roots = Rubric::findOne(['name' => 'Общество']);
$leaves = $roots->leaves()->all();
        foreach ($leaves as $leave) {
	       echo '<pre>'.print_r($leave->name,true).'</pre>';
        }
        //echo '<pre>'.print_r($roots,true).'</pre>';
        exit();
        
        $roots = Rubric::find()->leaves()->all();
        foreach ($roots as $root) {
	       echo '<pre>'.print_r($root->name,true).'</pre>';
        }
        //echo '<pre>'.print_r($roots,true).'</pre>';
        exit();
*/
        
        $searchModel = new RubricSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rubric model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rubric model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rubric();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Rubric model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rubric model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rubric model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rubric the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rubric::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
