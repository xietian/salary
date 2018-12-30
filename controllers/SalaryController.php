<?php

namespace app\controllers;

use app\services\auth\SalaryService;
use app\services\auth\UserSalaryFixedService;
use app\tools\OutTools;
use app\tools\SalaryFunction;
use Yii;
use app\models\Salary;
use app\models\SalarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SalaryController implements the CRUD actions for Salary model.
 */
class SalaryController extends Controller
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
     * Lists all Salary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $deptId = Yii::$app->request->get('dept_id');
        $userId = Yii::$app->request->get('user_id');
        $date = Yii::$app->request->get('date');
        if (empty($date)) {
            $date = date('Y-m-1');
        }
        $date = strtotime($date);
        $salaryService = new SalaryService();
        $res = $salaryService->getListByMonth($date, $deptId, $userId);
        $userList = $res['data']['users'];
        $deptList = $res['data']['depts'];
        $itemList = $res['data']['items'];
        $salaryList = $res['data']['salarys'];
        return $this->render('index', [
            'itemList' => $itemList,
            'userList' => $userList,
            'deptList' => $deptList,
            'salaryList' => $salaryList,
        ]);
    }

    /**
     * 设置
     */
    public function actionSet()
    {
        $userId = Yii::$app->request->post('user_id');
        $itemId = Yii::$app->request->post('item_id');
        $val = Yii::$app->request->post('val');
        $date = strtotime(Yii::$app->request->post('date') . "-1");
        $salaryService = new SalaryService();
        $res = $salaryService->setSalary($itemId, $userId, $val, $date);
        OutTools::outJsonP($res);
    }

    /**
     * @return string
     */
    public function actionBase()
    {
        $deptId = Yii::$app->request->get('dept_id');
        $userId = Yii::$app->request->get('user_id');
        $salaryService = new UserSalaryFixedService();
        $res = $salaryService->getList($deptId, $userId);
        $userList = $res['data']['users'];
        $deptList = $res['data']['depts'];
        $itemList = $res['data']['items'];
        $salaryList = $res['data']['salarys'];
        return $this->render('base', [
            'itemList' => $itemList,
            'userList' => $userList,
            'deptList' => $deptList,
            'salaryList' => $salaryList,
        ]);
    }

    /**
     * 设置基本工资
     */
    public function actionBset()
    {
        $userId = Yii::$app->request->post('user_id');
        $itemId = Yii::$app->request->post('item_id');
        $val = Yii::$app->request->post('val');
        $salaryService = new UserSalaryFixedService();
        $res = $salaryService->setSalary($itemId, $userId, $val);
        OutTools::outJsonP($res);
    }

    /**
     * Displays a single Salary model.
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
     * Creates a new Salary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Salary();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Salary model.
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
     * Deletes an existing Salary model.
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
     * Finds the Salary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Salary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Salary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
