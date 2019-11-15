<?php

namespace backend\modules\sprints\controllers;

use App\JiraApi;
use common\modules\labels\records\Label;
use Yii;
use common\modules\sprints\records\Sprint;
use common\modules\sprints\searches\SprintSearch;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SprintController implements the CRUD actions for Sprint model.
 */
class SprintController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sprint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SprintSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Sprint model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Sprint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sprint;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionImport()
    {
        $boards = \Yii::$app->jira->getBoards();

        foreach ($boards as $board) {
            $sprints = \Yii::$app->jira->getSprints($board['id']);

            foreach ($sprints as $sprint) {
                $model = Sprint::byJiraId($sprint['id']) ?? new Sprint();
                $model->setAttributes([
                    'jira_id' => $sprint['id'],
                    'state'   => $sprint['state'],
                    'name'    => $sprint['name'],
                    'start'   => date('Y-m-d H:i:s', strtotime($sprint['startDate'])) ?? '',
                    'end'     => date('Y-m-d H:i:s', strtotime($sprint['endDate'])) ?? '',
                    'goal'    => $sprint['goal'],
                    'board_id'   => $sprint['originBoardId'],
                    'board_name' => $sprint[$board['name']],
                ]);
                $model->save();
            }
        }
        return $this->redirect('index');
    }

    /**
     * Updates an existing Sprint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sprint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sprint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sprint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sprint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}