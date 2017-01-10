<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
                    'accept'  => ['POST'],
                    'decline' => ['POST'],
                ],
            ],
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'create', 'accept', 'decline'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'create', 'accept', 'decline'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();
		$model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

	public function actionAccept($id)
    {
        if($this->findModel($id)->accept()){
	        return $this->redirect(['user/view', 'id' => Yii::$app->user->identity->id]);
		} else {
			throw new ForbiddenHttpException('You don`t have access this order.');
		}
    }

	public function actionDecline($id)
    {
        if($this->findModel($id)->decline()){
	        return $this->redirect(['user/view', 'id' => Yii::$app->user->identity->id]);
		} else {
			throw new ForbiddenHttpException('You don`t have access this order.');
		}
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		$nickname = Yii::$app->user->identity->nickname;

        if (($model = Order::find()->where(['id' => $id])->andWhere('sender_name = :nick OR recipient_name = :nick', [':nick' => $nickname])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
