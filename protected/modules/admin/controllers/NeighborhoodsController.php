<?php

class NeighborhoodsController extends Controller
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
		$model=new Neighborhoods;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Neighborhoods']))
		{
			$model->city_id = $_POST['Neighborhoods']['city_id'];
            $model->name 	= trim($_POST['Neighborhoods']['name']);
            if(!empty($_POST['Neighborhoods']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = trim($_POST['Neighborhoods']['order']);
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
		$model 	= $this->loadModel($id);
        $city 	= Cities::model()->findByPK($model->city_id); //getting city data by city id
        $code 	= $city->country;
        $region = $city->region;

        $condition_region 	= array(':code' => $code);
        $condition_city 	= array(':code' => $code, ':region' => $region);
        $select_regions 	= Regions::model()->findAll('country=:code order by `order` DESC', $condition_region);
        $select_cities 		= Cities::model()->findAll('country=:code AND region=:region order by `order` DESC', $condition_city);
        $selected 			= array('country' => $city->country, 'region' => $city->region); //value for drop down selected
                
        // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Neighborhoods'])) {
            $model->city_id = $_POST['Neighborhoods']['city_id'];
            $model->name 	= trim($_POST['Neighborhoods']['name']);
            
            if(!empty($_POST['Neighborhoods']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = trim($_POST['Neighborhoods']['order']);
            }

			if($model->update()) //saving data
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,'region' => $select_regions,'city' => $select_cities, 'selected' => $selected
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
		$dataProvider=new CActiveDataProvider('Neighborhoods');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Neighborhoods('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Neighborhoods'])) {
            $model->attributes=$_GET['Neighborhoods'];
        }
        
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Neighborhoods::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='neighborhoods-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
