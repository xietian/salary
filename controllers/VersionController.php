<?php

namespace app\controllers;

use Yii;
use app\models\Version;
use app\models\VersionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * VersionController implements the CRUD actions for Version model.
 */
class VersionController extends Controller
{
    /**
     * @inheritdoc
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

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'multiple' => true,
                'disableCsrf' => true,
                'responseFormat' => Response::FORMAT_JSON,
                'responsePathParam' => 'path',
                'responseBaseUrlParam' => 'base_url',
                'responseUrlParam' => 'url',
                'responseDeleteUrlParam' => 'delete_url',
                'responseMimeTypeParam' => 'type',
                'responseNameParam' => 'name',
                'responseSizeParam' => 'size',
                'deleteRoute' => 'delete',
                'fileStorage' => 'fileStorage', // Yii::$app->get('fileStorage')
                'fileStorageParam' => 'fileStorage', // ?fileStorage=someStorageComponent
                'sessionKey' => '_uploadedFiles',
                'allowChangeFilestorage' => false,
                'validationRules' => [

                ],
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
//                    $file = $event->file;
//                    $img = ImageManagerStatic::make($file->read())->fit(500, 500);
//                    $file->put($img->encode());
//                     do something (resize, add watermark etc)
                }
            ], 'delete2' => [
                'class' => 'trntv\filekit\actions\DeleteAction',
                //'fileStorage' => 'fileStorageMy', // my custom fileStorage from configuration(such as in the upload action)
            ], 'view2' => [
                'class' => 'trntv\filekit\actions\ViewAction',
            ]
        ];
    }


    /**
     * Lists all Version models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Version model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Version model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Version();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Version::updateAll(array('is_latest' => 0), 'id!=:id', array(':id' => $model->id));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->release_time = time();
            $model->is_latest = 1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Version model.
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
     * Deletes an existing Version model.
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
     * Finds the Version model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Version the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Version::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
