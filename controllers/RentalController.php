<?php

namespace app\controllers;

use app\models\Rental;
use app\models\RentalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * RentalController implements the CRUD actions for Rental model.
 */
class RentalController extends Controller
{
    /**
     * @inheritDoc
     */

    public const MAX_BOOKS_NORMAL_USER=5;
    public const MAX_BOOKS_ADMIN_USER=50;

    public const INTERVAL_NORMAL_USER='14 days';
    public const INTERVAL_ADMIN_USER='28 days';
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Rental models.
     * @return mixed
     */


    public function actionRent($id)
    {
        $user = \Yii::$app->user->identity;
        $now = Date('Y-m-d');
        $limit = self::MAX_BOOKS_NORMAL_USER;

        $rentEnd = self::INTERVAL_NORMAL_USER;
        if ($user->is_admin) {
            $limit = self::MAX_BOOKS_ADMIN_USER;
            $rentEnd = self::INTERVAL_ADMIN_USER;
        }

        if (count($user->activeRentedBooks) >= $limit) {
               \Yii::$app->session->setFlash('error', 'Sorry, but you have reached the limit of ' . $limit . ' actively rented books');
               return $this->redirect(\Yii::$app->request->referrer ?: \Yii::$app->homeUrl);
        }


        $pivot = new Rental();
        $pivot->user_id = $user->id;
        $pivot->book_id = $id;
        $pivot->rent_start = $now;
        $pivot->rent_end = $now->add(\DateInterval::createFromDateString($rentEnd));
        $pivot->save();

        return $this->redirect('/my-rental');
    }
    public function actionIndex()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rental model.
     * @param int $id ID
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
     * Creates a new Rental model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rental();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Rental model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rental model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rental model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Rental the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rental::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
