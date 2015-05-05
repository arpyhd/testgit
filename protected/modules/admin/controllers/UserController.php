<?php

class UserController extends Controller
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
				'actions'=>array('login','resetForm','resetPassword'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','index','view','logout','admin','delete','email'),
				'users'=>array('@'),
			),
			/*array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
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
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->added_date = date('Y-m-d h:i:s',time());
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'user_type' => $this->getusertype(),
			'status' => $this->getstatus(),
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->added_date = date('Y-m-d h:i:s',time());
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'user_type' => $this->getusertype(),
			'status' => $this->getstatus(),
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
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		unset(Yii::app()->request->cookies['from_date']);
		unset(Yii::app()->request->cookies['to_date']);
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(!empty($_GET))
		  {
			Yii::app()->request->cookies['from_date'] = new CHttpCookie('from_date', $_GET['from_date']);  // define cookie for from_date
			Yii::app()->request->cookies['to_date'] = new CHttpCookie('to_date', $_GET['to_date']);
			$model->from_date = $_GET['from_date'];
			$model->to_date = $_GET['to_date'];
		}
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];
		 
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionLogin()
	{
		
		$model = new UserLogin;		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='UserLogin')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
                
		if(isset($_POST['UserLogin']))
		{			
			$model->attributes=$_POST['UserLogin'];
                        
			// validate user input and redirect to the previous page if valid
                        
			if($model->alogin()) {
                                $this->redirect(Yii::app()->createUrl("admin/dashboard/index"));
			}else{
				Yii::app()->user->setFlash('error',"Unauthorized Email.!");
				$this->redirect(Yii::app()->createUrl("admin/user/login"));
			}
				
		}		
		// display the login form
		
		$this->render('login', array('model'=>$model));
	}
        
    /**
    * Logs out the current user and redirect to homepage.
    */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->createUrl("admin/user/login"));
    }
	
	public function actionResetForm()
	{
		$this->layout = "//layouts/admcolumn1";
		$model = new Users;
		if(isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$id = $model->reset($_POST['User']['email']);
			if($id){
				$pass_hash = md5($id.'_'.strtotime('now'));
				$id_hash = md5($id);
				User::model()->updateByPk($id, array('pass_hash' => $pass_hash, 'id_hash' => $id_hash));
				$this->redirect(array('resetPassword', 'h'=>$id_hash.'_'.$pass_hash));
			}
			else{
				Yii::app()->user->setFlash('error',"Please Provide A Valid Email.!");
			}
		}
                
		$this->render('resetform',array('model'=>$model));		
	}

	public function actionResetPassword($h)
	{	
		$this->layout = '//layouts/admcolumn2';
		$model = new User;	
		$exp_h = explode('_', $h);
		$criteria = new CDbCriteria;
		$criteria->condition = "id_hash = '".$exp_h['0']."' AND pass_hash = '".$exp_h['1']."'";
		$user = User::model()->find($criteria);
		$err = array();
		
		if(isset($_POST['User']))
		{
			if($_POST['User']['password'] == '')
			{
				$err['password'] = 'Please enter new password';
			}
			if($_POST['User']['c_password'] == '')
			{
				$err['c_password'] = 'Please enter confirm password';
			}
			if($_POST['User']['c_password'] != $_POST['User']['password'])
			{
				$err['common'] = 'Password and Confirm password not matched';
			}
			if(count($err) == 0)
			{
				$password = md5($_POST['User']['password']);
				User::model()->updateByPk($user->id, array('password' => $password, 'pass_hash' => '' ));
				Yii::app()->user->setFlash('success',"Password updated successfully.!");
				$this->redirect(array('/admin/user/login'));
			}
		}

		$this->render('resetpassword', array('model'=>$model, 'err'=>$err));
	}
        
    /**
	 * Sends email to users as per rows selected
	 */
    public function actionEmail()
	{
        $model = new EmailForm();
        if(empty($_POST["selectedItems"])){
            if(empty($_POST["return_url"])){
                $_POST["return_url"] = "admin/user/admin";
            }
            Yii::app()->user->setFlash('warning',"Please select atleast one email recipient.");
            $this->redirect(Yii::app()->createUrl($_POST["return_url"]));
        }
        
        if(isset($_POST['EmailForm'])) {
            $model->attributes = $_POST["EmailForm"];
            if($model->validate()){
                if($_POST["allSelected"]=="allSelected"){
                    $users = Users::model()->findAll();
                    foreach($users as $user){
                        $user_model = Users::model()->findByPk($user->id);
                        $emailQueue = new EmailQueue();
                        $emailQueue->email_title = $model->subject;
                        $emailQueue->email_content = $model->content;
                        $emailQueue->email_from = Yii::app()->params["adminEmail"];
                        $emailQueue->email_to = $user_model->email;
                        $emailQueue->email_queued_date = date("Y-m-d H:i:s");
                        $emailQueue->email_status = 0;
                        $emailQueue->save();
                    }
                }else{
                    foreach($_POST["selectedItems"] as $selectedItems){
                        $user_model = Users::model()->findByPk($selectedItems);
                        $emailQueue = new EmailQueue();
                        $emailQueue->email_title = $model->subject;
                        $emailQueue->email_content = $model->content;
                        $emailQueue->email_from = Yii::app()->params["adminEmail"];
                        $emailQueue->email_to = $user_model->email;
                        $emailQueue->email_queued_date = date("Y-m-d H:i:s");
                        $emailQueue->email_status = 0;
                        $emailQueue->save();                       
                    }
                }
                Yii::app()->user->setFlash('success',"Email have been queued successfully to send!");
                $this->redirect(Yii::app()->createUrl($_POST["return_url"])); 
            }
        }

        $this->render('emailForm',array('model'=>$model));		
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function getusertype()
	{
		$user_type=array(
			'Admin' => "Admin",
			'User' => "User",
			'Business' => "Business"
		);
		return $user_type;
	}
	protected function getstatus()
	{
		$status=array(
			'Active' => "Active",
			'Inactive' => "Inactive"
			
		);
		return $status;
	}
}
