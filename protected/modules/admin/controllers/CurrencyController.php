<?php

class CurrencyController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admcolumn1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Currency;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Currency'])) {
			$id 		= $_POST['Currency']['country_id'];
            $country 	= Countries::model()->findByPk($id); // getting country data by pk
            
            $model -> country 			= $country->name;
            $model -> code 				= $_POST['Currency']['code'];
            $model -> currency_name 	= $_POST['Currency']['currency_name'];
            $model -> symbol 			= $_POST['Currency']['symbol'];
            $model -> country_id 		= $id;
            
            if(!empty($_POST['Currency']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = $_POST['Currency']['order'];
            }
            
            if($model->save()) //saving data
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this -> loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Currency'])) {
			$id 		= $_POST['Currency']['country_id'];
            $country 	= Countries::model()->findByPk($id); // getting country data by pk
            
            $model -> country 		= $country -> name;
            $model -> code 			= $_POST['Currency']['code'];
            $model -> currency_name = $_POST['Currency']['currency_name'];
            $model -> symbol 		= $_POST['Currency']['symbol'];
            $model -> country_id 	= $id;
            
            if(!empty($_POST['Currency']['order'])) { //insert order only if it is entered otherwise default will be there
                $model -> order = $_POST['Currency']['order'];
            }
                        
			if($model->save()) // saving data
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $dataProvider=new CActiveDataProvider('Currency');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Currency('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Currency']))
			$model->attributes=$_GET['Currency'];
                
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Location the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Currency::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Location $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='currency-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}