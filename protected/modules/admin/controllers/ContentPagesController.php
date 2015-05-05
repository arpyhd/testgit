<?php

class ContentPagesController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('updateTranslations', 'redirectTranslations','index','view','create','update','admin','delete'),
				'users'=>array('@'),
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
		$model=new ContentPages;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContentPages']))
		{
			$model->attributes=$_POST['ContentPages'];
			if($model->save()){
                            Yii::app()->user->setFlash("success","New source message and its translations have been added.");
                            $this->redirect(array('admin'));
                        }
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContentPages']))
		{
			$model->attributes=$_POST['ContentPages'];
			if($model->save()){
                            Yii::app()->user->setFlash("success","New source message and its translations have been added.");
                            $this->redirect(array('admin'));
                        }
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
		$dataProvider=new CActiveDataProvider('ContentPages');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ContentPages('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ContentPages']))
			$model->attributes=$_GET['ContentPages'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
        /**
	 * Redirects to the update translate form. Adds translation if no translation available
         * @param integer $id the ID of the content page
	 */
        public function actionRedirectTranslations($id){
            $translation = ContentTranslations::model()->findByAttributes(array("language"=>$_POST["language"],"content_id"=>$id));
            if(!$translation){
                $translation = new ContentTranslations();
                $translation->content_id = $id;
                $translation->language = $_POST["language"];
                $translation->add_datetime = date("Y-m-d H:i:s");
                $translation->save();
            }
            $this->redirect(Yii::app()->createUrl("admin/contentTranslations/update", array("id"=>$translation->id)));
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ContentPages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ContentPages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
	/**
	 * Performs the AJAX validation.
	 * @param ContentPages $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='content-pages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
