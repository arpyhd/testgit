<?php

class BusinessController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete', 'dynamicdata'),
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
	 * 
	 * Reviewed by _uJJwAL_
	 */
	public function actionCreate()
	{
		$model = new Business;
		$user  = new Users; 

		if(isset($_POST['Business'])) {
			$valid = true;
			$transaction = Yii::app()->db->beginTransaction();

            $user = new Users();
            $user -> attributes 	= $_POST['Users'];
            $user -> first_name 	= $_POST['Business']["first_name"];
            $user -> last_name 		= $_POST['Business']["last_name"];
            $user -> user_type 		= "user";
            $user -> password 		= md5($user -> password);
            $user -> pass_hash 		= md5(strtotime("now"));
            $user -> disabled 		= !empty($_POST['Business']["disabled"]) ? $_POST['Business']['disabled'] : null;
            $valid = $valid && $user -> save();

            if($valid) {
				$model -> attributes 	= $_POST['Business'];
				$model -> user_id 		= $user -> id;
	           
	            $valid = $valid && $model -> save();
	        }

	        if($valid) {
	        	try {
	        		$transaction -> commit();

	        		Yii::app()->user->setFlash('success', "Business Created Successfully!");
                	$this -> redirect(array('admin'));
	        	} catch(Exception $e) {
	        		$transaction -> rollback();

	        		Yii::app()->user->setFlash('error', "Error in Creating Business!");
	        	}
	        } else {
	        	echo CActiveForm::validate($model) || CActiveForm::validate($user);
	        	Yii::app()->user->setFlash('error', "Error in Creating Business!");
	        }
		}

		$user -> password = "";

		$this->render('create',array('model'=>$model, 'user' => $user));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 *
	 * Reviewed by _uJJwAL_
	 */
	public function actionUpdate($id)
	{
		$valid 			= true;
		$transaction 	= Yii::app()->db->beginTransaction();
		
		$model 	= $this -> loadModel($id);
		$user 	= $model -> user;

		if(Yii::app()->request->isPostRequest) {
			if(isset($_POST['Users'])) {
				$user -> email 		= $_POST['Users']['email'];
				$user -> first_name = $_POST['Business']["first_name"];
            	$user -> last_name 	= $_POST['Business']["last_name"];
		        $user -> disabled 	= $_POST['Business']["disabled"];
		        
		        if(isset($_POST['Users']['password']) && !empty($_POST['Users']["password"])){
		            $user -> password = md5($_POST['Users']["password"]);
		        } 
		
	        	$valid = $valid && $user -> save();
	    	}

			if(isset($_POST['Business'])) {
				$model -> attributes 	= $_POST['Business'];				
				$valid = $valid && $model->save();
			}

			if($valid) {
				try {
					$transaction -> commit();

	                Yii::app()->user->setFlash("success","Business has been updated.");
	                $this->redirect(array('admin'));          
				} catch(Exception $e) {
					Yii::app()->user->setFlash("error","Business has been updated.");
				}
			} else {
				echo CActiveForm::validate($model) || CActiveForm::validate($user);
				Yii::app()->user->setFlash("error","Business has been updated.");
			}
		}

        $user -> password = "";
		$this->render('update',array('model' => $model, 'user' => $user));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest) {
            $business 	= $this -> loadModel($id);
            $user_id 	= $business -> user_id;
                    
			$this -> loadModel($id)->delete();
            Users::model()->deleteByPk($user_id);

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
		$this->redirect("admin");
        }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        unset(Yii::app()->request->cookies['from_date']);
		unset(Yii::app()->request->cookies['to_date']);
		
		$model = new Business('search');
		$model -> unsetAttributes();  // clear any default values
                
        if(!empty($_GET)) {
            Yii::app()->request->cookies['from_date'] 	= new CHttpCookie('from_date', $_GET['from_date']);  // define cookie for from_date
            Yii::app()->request->cookies['to_date'] 	= new CHttpCookie('to_date', $_GET['to_date']);
            
            $model -> from_date = $_GET['from_date'];
            $model -> to_date 	= $_GET['to_date'];
        }

		if(isset($_GET['Business']))
			$model -> attributes = $_GET['Business'];

		$this->render('admin',array('model' => $model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
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
	 * @param CModel the model to be validated
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

}
