<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\AttachmentSearch;
use app\models\ResourcesFactory;
use app\services\auth\FactoryResService;
use app\tools\OutFormat;
use app\tools\OutTools;
use app\tools\VodTools;
use Yii;
use app\models\Resources;
use app\models\ResourcesSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ResourcesController implements the CRUD actions for Resources model.
 */
class ResourcesController extends Controller
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

    /**
     * Lists all Resources models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResourcesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 获取凭证
     */
    public function actionAuth()
    {
        $title = Yii::$app->request->post('title');
        $fileName = Yii::$app->request->post('fileName');
        $vodTools = new VodTools();
        try {
            $res = $vodTools->create_upload_video($title, $fileName);
            OutTools::outJsonP(OutTools::success($res));
        } catch (\Exception $e) {
            OutTools::outJsonP(OutTools::error($e->getCode(), $e->getMessage()));
        }
    }

    /**
     * Displays a single Resources model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //获取视频信息
        if ($model->convert_status == Resources::CONVERT_STATUS_CONVERTING) {
            $vodtools = new VodTools();
            try {
                $info = $vodtools->get_play_info($model->third_resource_id);
                $data = array();
                if ("Normal" == $info['VideoBase']['Status']) {
                    foreach ($info['PlayInfoList']['PlayInfo'] as $rec) {
                        if ($rec['Format'] == 'mp4') {
                            $data['duration'] = $rec['Duration'];
                            $data['url'] = $rec['PlayURL'];
                            $data['size'] = $rec['Size'];
                            $data['thumb'] = $info['VideoBase']['CoverURL'];
                            $data['convert_status'] = Resources::CONVERT_STATUS_FISHING;
                            $data['suffix'] = $rec['Format'];
                            Resources::updateAll($data, 'id=:id', array(':id' => $id));
                        }
                    }

                    $model = $this->findModel($id);
                }
            } catch (\Exception $e) {
                //还在转码中
            }

        }
        $searchModel = new AttachmentSearch(["rid" => (int)$id]);
        $dataProvider = $searchModel->search(["rid" => (int)$id]);
        return $this->render('view', [
            'model' => $model, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel
        ]);
    }

    /**
     * Creates a new Resources model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Resources();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (empty($model->dept_ids) == false) {
                $service = new FactoryResService();
                $service->addBatch($model->id, $model->dept_ids);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->size = 0;
            $model->convert_status = Resources::CONVERT_STATUS_CONVERTING;
            $model->visit_num = 0;
            $model->duration = 0;
            $model->created_at = time();
            $model->creator_id = Yii::$app->getUser()->getId();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Resources model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //上传
        if (isset($_POST['third_resource_id'])) {
            if ($model->third_resource_id != isset($_POST['third_resource_id'])) {
                $model->convert_status = Resources::CONVERT_STATUS_CONVERTING;
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $service = new FactoryResService();
            $service->updateBatch($model->id, $model->dept_ids);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $service = new FactoryResService();
            $model->dept_ids = $service->getFactoryIdArray($model->id);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Resources model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Resources model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Resources the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resources::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
