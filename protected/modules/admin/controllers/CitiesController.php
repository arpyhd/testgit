<?php

class CitiesController extends Controller
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
        $model = $this->loadModel($id);
        $region = Regions::model()->findAllByAttributes(array('id' => $model->region, 'country' => $model->country)); //getting region by region value and country code
        $this->render('view',array('model'=> $model,'region' => $region));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cities;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cities']))
		{
			$model->country = $_POST['Cities']['country'];
            $model->region 	= $_POST['Cities']['region'];
            $model->name 	= trim($_POST['Cities']['name']);
            
            if(!empty($_POST['Cities']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = trim($_POST['Cities']['order']);
            }

            if($model->save()) //saving data
				$this->redirect(array('view','id'=>$model->id));
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
		$model=$this->loadModel($id);
        $code = $model->country;
        $condition = array(':code' => $code);
        $region = Regions::model()->findAll('country=:code order by `order` DESC', $condition);// getting regions by country code
        // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                
        if(isset($_POST['Cities'])) {
            $model->region 	= $_POST['Cities']['region'];
            $model->country = $_POST['Cities']['country'];
            $model->name 	= trim($_POST['Cities']['name']);
            
            if(!empty($_POST['Cities']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = trim($_POST['Cities']['order']);
            }
                
            if($model->update()) // saving data
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model, 'region' =>$region
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cities');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cities('search');
        $model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cities']))
			$model->attributes=$_GET['Cities'];

		$this->render('admin',array('model'=>$model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Cities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cities-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
