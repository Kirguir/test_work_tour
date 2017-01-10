<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\OrderSearch;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);

		if($model->nickname !== Yii::$app->user->identity->nickname){
			throw new ForbiddenHttpException('You don`t access for this page.');
		}

		$searchModel = new OrderSearch();

		$dataProviderSend = new ActiveDataProvider([
            'query' => $model->getOrdersSended()->with('senderName', 'recipientName')->orderBy(['process_time' => SORT_DESC]),
        ]);

		$dataProviderReceive = new ActiveDataProvider([
            'query' => $model->getOrdersReceived()->with('senderName', 'recipientName')->orderBy(['process_time' => SORT_DESC]),
        ]);

        return $this->render('view', [
            'model' => $model,
			'searchModel' => $searchModel,
            'dataProviderSend' => $dataProviderSend,
            'dataProviderReceive' => $dataProviderReceive,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
