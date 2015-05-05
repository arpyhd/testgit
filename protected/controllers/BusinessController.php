<?php

class BusinessController extends Controller
{
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main-business';

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
				'actions'=>array('create', 'update'),
				'users'=>array('@'),
			),			
			array('deny',  // deny all users
				'actions'=>array('index','view','admin','delete'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            //echo Yii::app()->user->getId();
            //exit;
		$model = new Business;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		Yii::app()->user->setFlash('success', 'Business Details:');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$this -> title 		= Yii::t('business', "Business Account Details");
                $this -> subtitle 	= Yii::t('business', 'Click on the details to modify it');

		if(isset($_POST['Business'])) {
			$model -> attributes = $_POST['Business'];
			$model -> user_id 	 = Yii::app()->user->getId();

			if($model->save()) {
				Yii::app()->user->setFlash('success', 'Business Details: Successfully Created');
                $this->redirect(Yii::app()->createUrl('location/admin'));
			}
		}

		$this->render('_form',array('model' => $model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id=null) {
		if(!is_null($id)) {
			$model = $this->loadModel($id);

			Yii::app()->user->setFlash('success', 'Business Details:');

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			$this->title = Yii::t('business', "Business Account Details");
	        $this->subtitle = Yii::t('business', 'Click on the details to modify it');

			if(isset($_POST['Business']))
			{
				$model -> attributes = $_POST['Business'];

				if($model->save()) {
					Yii::app()->user->setFlash('success', 'Business Details: Successfully Updated');
				}
			}

			$this->render('_form',array('model' => $model));
		} else {
			$this -> redirect(Yii::app()->urlManager->createUrl('business/create'));
		}
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
		$dataProvider=new CActiveDataProvider('Business');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Business('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Business']))
			$model->attributes=$_GET['Business'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Business the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Business::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Business $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='business-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * Output data for populating dynamic lists based on model type
     * @author Nathanphan
     * @return void
     */
    public function actionDynamicdata()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $condition = array(':id' => (int) $_POST['id']);
            $model = isset($_POST['model']) ? ucfirst($_POST['model']) : '';

            if(class_exists($model)) {
                $data = $model::model()->find('country_id=:id', $condition);
                echo $data->id;
            }

            Yii::app()->end();
        }
    }

    public function actionOverview()
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('business', 'country', 'neighborhood', 'city');
        $criteria->together = true;
        $criteria->condition = 't.business_id = :bId';
        $criteria->params = array( ':bId' => $this->business );

        $locationList = new CActiveDataProvider('Locations', array(
            'criteria'=>$criteria,
        ));

        $this->render('overview', array('locationList' => $locationList));
    }
    
	
	
}
