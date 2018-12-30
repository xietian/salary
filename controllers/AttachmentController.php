<?php

namespace app\controllers;

use Yii;
use app\models\Attachment;
use app\models\AttachmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
/**
 * AttachmentController implements the CRUD actions for Attachment model.
 */
class AttachmentController extends Controller
{

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

    /**
     * Lists all Attachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rid = Yii::$app->request->get('rid');
        if(empty($rid)){
            $this->redirect(Yii::$app->getUrlManager()->createUrl("resources/index"));
        }
        $searchModel = new AttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rid' => $rid,
        ]);
    }

    /**
     * Displays a single Attachment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $rid = Yii::$app->request->get('rid');
        if(empty($rid)){
            $this->redirect(Yii::$app->getUrlManager()->createUrl("resources/index"));
        }
        $model = new Attachment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/resources/view', 'id' => $model->rid]);
        } else {
            $model->created_at = time();
            $model->creator_id = Yii::$app->getUser()->getId();
            $model->size = 0;
            $model->rid = $rid;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Attachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/resources/view', 'id' => $model->rid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Attachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['resources/view?id='.$model->rid]);
    }

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Attachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
