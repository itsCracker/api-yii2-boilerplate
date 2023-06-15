<?php

namespace modules\main\controllers;

use Yii;
use models\Mode;
use search\ModeSearch;
use components\Controller;
use yii\web\NotFoundHttpException;

class ModeController extends Controller
{
    public function actionIndex()
    {
        $search['ModeSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new ModeSearch();
        $dataProvider = $searchModel->search($search);

        return $this->apiSuccess([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Mode'] = Yii::$app->request->getBodyParams();
        $model = new Mode();

        if($model->load($dataRequest) && ($model->validate() &&  $model->mode())) {
            return $this->apiGenerated($model);
        }

        return $this->apiValidated($model->errors);
    }

    public function actionUpdate($mode_id)
    {
        $dataRequest['Mode'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($mode_id);
        if($model->load($dataRequest) && $model->save()) {
            return $this->apiUpdateDelete($model);
        }

        return $this->apiValidated($model->errors);
    }

    public function actionView($mode_id)
    {
        return $this->apiSuccess($this->findModel($mode_id));
    }

    public function actionDelete($mode_id)
    {
        if($this->findModel($mode_id)->delete()) {
            return $this->apiUpdateDelete(true);
        }
        return $this->apiUpdateDelete(false);
    }

    protected function findModel($mode_id)
    {
        if(($model = Mode::findOne($mode_id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('This record does not exist');
        }
    }
}


