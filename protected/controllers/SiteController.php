<?php

class SiteController extends Controller {

    public $layout = '//layouts/main-yiibootstrap';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     * @author Tonyweb  
     * Reviewed by _uJJwAL_
     */
    public function actionIndex() {

        $favResult = array();
        if(Yii::app()->user->id) {
            $model = new Favourite;
            
            if(isset($_REQUEST['Favourite'])) {
                 $model -> attributes     = $_REQUEST['Favourite'];
                 $model -> business_name  = $_REQUEST['Favourite']['business_name'];
                 $model -> location_name  = $_REQUEST['Favourite']['location_name'];
            }
			
            $favResult = $model;
        }
        
	 

        if(isset(Yii::app()->request->cookies['city']->value)) {
        #if(isset($_COOKIE['city'])) {
            #$_cities = json_decode($_COOKIE['city']);            
            $_cities = json_decode(Yii::app()->request->cookies['city']->value);
            $this->render('index', array('city' => $_cities -> city, 'city_id' => $_cities -> city_id, 'is_city' => $_cities -> is_city, 'favResult' => $favResult));    
        } else {           
            $this->render('index',array('favResult' => $favResult));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Reviewed By: _uJJwAL_
     * User Signup
     *
     */
    public function actionSignup() {
        
        
        $model = new Users;
        $securityQuestion = SecurityQuestion::model()->findAll('disabled is null');
        $registrationType = Usertype::model()->findAll('disabled is null');                       
        
        if($_GET['type'] != md5('usr'))
        {
            $registrationType = array_reverse($registrationType, true);
        }

        $model->scenario = 'register';

        $this->title = Yii::t('signup', "Create user Account");
        $this->subtitle = Yii::t('signup', 'Welcome');

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $user = Users::model()->findByAttributes(array('email' => $model->email));
            if (empty($user)) {
                $selectedUserType = Usertype::model()->findByAttributes(array('id' => $_POST['Users']['registration_type']));
                $model->password = md5($model->password);
                $model->pass_hash = md5(strtotime("now"));
                $model->disabled = 1;
                $model->user_type_id = $_POST['Users']['registration_type'];
                $model->user_type = $selectedUserType->type;
                $model->added_date = date('Y-m-d H:i:s', time());

                if ($model->save()) {
                    $utilityFunction = new UtilityFunction();
                    
                    if($model->user_type == 'user')
                    {
                        $utilityFunction->sendMailOnSignup($model->email, $model->pass_hash);
                    }
                    else
                    {
                        $utilityFunction->sendMailOnSignup($model->email, $model->pass_hash,$model->user_type_id);
                    }

                    Yii::app()->user->setFlash('success', 'Activate your account by clicking on activation link');
                    $this->redirect(array('login'));
                }
            } else {
                Yii::app()->user->setFlash('error', 'Email Address Already Exist');
            }
        }

        $this->render('signup', array('model' => $model, 
            'securityQuestion' => $securityQuestion,
            'registrationType' => $registrationType));
    }

    /**
     * Reviewed by _uJJwAL_
     * Account Verification
     *
     */
    public function actionVerification() {
        
        if (isset($_GET['actid'])) {
            $model = Users::model()->findByAttributes(array('pass_hash' => $_GET['actid']));
            if (!empty($model)) {
                if($model->disabled != null)
                {
                    $model->disabled = null;
                    if ($model->save()) {
                        $modelUserLogin = new UserLogin;
                        $modelUserLogin->email = $model->email;
                        $modelUserLogin->password = $model->password;
                        if ($modelUserLogin->login(True)) {
                            if($model->user_type == 'user')
                            {
                                $this->redirect(array('/page/index/slug/welcome-page'));
                            }
                            else 
                            {
                                if($model->user_type == 'business')
                                {
                                    $this->redirect(array('/page/index/slug/welcome-page-2'));
                                }
                            }
                        }
                        Yii::app()->user->setFlash('success', "Your email has been activated!");
                    } else {
                        Yii::app()->user->setFlash('warning', "Activation Failed!");
                    }
                }
                else{
                    Yii::app()->user->setFlash('success', "Your account has already been activated earlier");
                }
            } else {
                Yii::app()->user->setFlash('warning', "Invalid Token");
            }
        } else {
            Yii::app()->user->setFlash('warning', "Invalid Token");
        }
        
        $this->redirect(array('login'));
    }

    /**
     * Reviewed by _uJJwAL_
     * Forget Password
     *
     */
    public function actionForget() {
        if (isset($_POST['forget']) && $_POST['forget'] != '') {
            $emailID = $_POST['forgetEmail'];

            $model = Users::model()->findByAttributes(array('email' => $emailID));
            $idHash = md5($model->id . strtotime("now"));

            if (!empty($model)) {
                $model->id_hash = $idHash;
                if ($model->save()) {
                    $utilityFunction = new UtilityFunction();
                    $utilityFunction->sendMailOnForgetPassword($emailID, $idHash);

                    Yii::app()->user->setFlash('success', 'An email has been sent to your email account!');
                    $this->redirect(array('login'));
                } else {
                    Yii::app()->user->setFlash('warning', 'Invalid Email Address!');
                }
            } else {
                Yii::app()->user->setFlash('warning', 'Invalid Email Address!');
            }
        }

        $this->render('forget');
    }

    /**
     * Reviewed by _uJJwAL_
     * Change password After Forget password
     *
     */
    public function actionNewpassword() {
		if(isset($_GET['changepassword']) && isset($_REQUEST['newpassword'])) {
		    $idHash = $_GET['changepassword'];
			$model = Users::model()->findByAttributes(array('id_hash' => $idHash));

            if($model) {
    			$model -> password = md5($_REQUEST['newpassword']);
    			if($model->save()) {
    				Yii::app()->user->setFlash('success', "Password Changed Successfully!");
    			} else {
    				Yii::app()->user->setFlash('warning', "Error in password change!");
    			}
            } else {
                Yii::app()->user->setFlash('warning', "Error in password change!");
            }

			$this->redirect(array('login'));
		} else {
		  $this->render('newpassword');  
        }	
	}

    /**
     * Reviewed by _uJJwAL_
     * Login 
     */
    public function actionLogin() {
        $model = new UserLogin;

        $this->title = Yii::t('signup', "Create your business Account");
        $this->subtitle = Yii::t('signup', 'Text');

        if (Yii::app()->user->getId() !== null) {
            $this->redirect(Yii::app()->user->returnUrl);
        }

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['UserLogin'])) {
		//die(Yii::app()->user->returnUrl);
            $model->attributes = $_POST['UserLogin'];

            ///   set cookies 
            if ($_POST['UserLogin']['rememberMe']) {
                $username = $_POST['UserLogin']['email'];
                $password = $_POST['UserLogin']['password'];

                $cookies = array(
                    "email" => $username,
                    "password" => $password,
                );

                setcookie('cookie', serialize($cookies), time() + 365 * 24 * 3600);
            }
            
            if ($model->validate() && $model->login()) {
                
                if (Yii::app()->user->returnUrl == '/')//as standard, go to dashboard
                {
                    //echo $_POST['userType'];
                    $this->redirect('/site');//$this->redirect('/dashboard');
                }
                else
                    $this->redirect(Yii::app()->user->returnUrl);
                
            }
        }

		
        /* ---------------------------------------Cookies Based Login------------------------------------------------- */

        if ($_COOKIE['cookie'] != '' && isset($_COOKIE['cookie'])) {
            $data = unserialize($_COOKIE['cookie']);
            $model->attributes = $data;
            $username = $data['email'];
            $password = $data['password'];

            if ($model->login()) {
                if (Yii::app()->user->returnUrl == '/')//as standard, go to dashboard
                    $this->redirect('/site');
                else
                    $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        // display the login form
        //$this->render('login', array('model' => $model));
        $this->render('login', array('model' => $model,'verified'=>$verified));
    }
    
    
    public function actionUserLogin() {
        $model = new UserLogin;

        $this->title = Yii::t('signup', "Create your business Account");
        $this->subtitle = Yii::t('signup', 'Text');

        if (Yii::app()->user->getId() !== null) {
            $this->redirect(Yii::app()->user->returnUrl);
        }

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['UserLogin'])) {
		//die(Yii::app()->user->returnUrl);
            $model->attributes = $_POST['UserLogin'];

            ///   set cookies 
            if ($_POST['UserLogin']['rememberMe']) {
                $username = $_POST['UserLogin']['email'];
                $password = $_POST['UserLogin']['password'];

                $cookies = array(
                    "email" => $username,
                    "password" => $password,
                );

                setcookie('cookie', serialize($cookies), time() + 365 * 24 * 3600);
            }

            if ($model->validate() && $model->login()) {
                if (Yii::app()->user->returnUrl == '/')//as standard, go to dashboard
                    $this->redirect('/site');//$this->redirect('/dashboard');
                else
                    $this->redirect(Yii::app()->user->returnUrl);
            }
        }

		
        /* ---------------------------------------Cookies Based Login------------------------------------------------- */

        if ($_COOKIE['cookie'] != '' && isset($_COOKIE['cookie'])) {
            $data = unserialize($_COOKIE['cookie']);
            $model->attributes = $data;
            $username = $data['email'];
            $password = $data['password'];

            if ($model->login()) {
                if (Yii::app()->user->returnUrl == '/')//as standard, go to dashboard
                    $this->redirect('/site');
                else
                    $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        // display the login form
        $this->render('userlogin', array('model' => $model));
    }

		
    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        setcookie('cookie', serialize($cookies), time() - 3600);
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Set language cookie when selecting language option
     */
    public function actionLanguage() {
        if (isset($_POST['_lang'])) {
            $cookie = new CHttpCookie('language', $_POST['_lang']);
            $cookie->expire = time() + 60 * 60 * 24 * 180;
            Yii::app()->request->cookies['language'] = $cookie;
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionHelp() {
        $this->renderPartial('_help');
    }

    /*
      action: getproduct
      Get product name for searchbox autocomplete
      Author: jayeshaghadi <jayesh.aghadiinfotech@gmail.com>
     */

    public function actiongetproduct() {

        $terms = $_REQUEST['term'];

        $sql = "SELECT p.name FROM products as p RIGHT JOIN product_detail as pd ON (p.id=pd.product_id) where name LIKE '%" . $terms . "%' GROUP BY p.name";

        //echo $sql;die;
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $product_array = array();
        foreach ((array) $result as $row) {
			//$name = str_replace('-',' ',str_replace("'","",$row['name']));
           // $product_array[] = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name);
            $product_array[] = $row['name'];
        }
        echo json_encode($product_array);
        die();
    }
	
	/*
      action: bussiness
      Display all the product related to particular bussiness via bussiness id
      Author: jayeshaghadi <jayesh.aghadiinfotech@gmail.com>
     */

    public function actionbussiness() {
		 $bussiness_id = isset($_GET['bid']) ? $_GET['bid'] : '';
		 $catalogue_id = isset($_GET['catalogue']) ? $_GET['catalogue'] : '';
		 if($bussiness_id != '' && $catalogue_id != ''){
			$condition = '';
			$selection = array();
			if(isset($_POST['bussinesssubmit'])){
				 if($_POST['product_name'] != ''){
					 $condition .= ' AND p.name LIKE "%'.$_POST['product_name'].'%"';
					 $selection['pname'] = $_POST['product_name'];
				 }
				 
				 if($_POST['category_id'] != ''){
					 $condition .= ' AND pd.category_id='.$_POST['category_id'];
					 $selection['cat_id'] = $_POST['category_id'];
				 }
			}
			//location details
			$loactionsdetails = Locations::model()->getLocationDetails($bussiness_id,$catalogue_id);
			
			//get catalogue categories
			$categories = Catalogue::model()->getCatalogueCategoryByCatalogueId($catalogue_id);
			
			//get catalogue products
			$result = Catalogue::model()->getCatalogueProduct($bussiness_id,$catalogue_id,$condition);
			
			$totals = count($result);
			// paginating
			$pages = new CPagination($totals);
			$pageSize = 10;
			$this->layout = '/layouts/internal-yiibootstrap';
			$this->render('business',array(
				'data' => $result,
				'business_detail'=>$loactionsdetails,
				'bussiness_id' =>$bussiness_id,
				'catalogue_id' => $catalogue_id,
				'categories' => $categories,
				'selection' => $selection,
				'totals' => $totals,
				'pages' => $pages,
				'page_size' => $pageSize
				)
			);
		 }else{
			 
			 $this->render('business',array(
			 		'data' => array()
			 	)
			);
		 }
		 
    }
    
    /**
     * Reviewed by _uJJwAL_
     */
    public function actionFavourite()
    {
       
        $id             = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $lid            = isset($_REQUEST['lid'])?$_REQUEST['lid']:0;
        $case           = isset($_REQUEST['case'])?$_REQUEST['case']:"";
        $cityid         = isset($_REQUEST['cityid'])?$_REQUEST['cityid']:0;
		$catalogue_id   = isset($_REQUEST['catid'])?$_REQUEST['catid']:0;
		$keyword        = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:'';
        
        if(Yii::app()->user->id){
			$model=Favourite::model()->find(array("condition"=>"userid=".Yii::app()->user->id . " and catalogue_id=".$catalogue_id." and business_id=".$id));
			
			if(!$model){
				$model = new Favourite;
				$model -> business_id = $id;
				$model -> userid = Yii::app()->user->id;
			}

			$model -> keyword = $keyword;
			
            if($catalogue_id){
			    $model -> catalogue_id = $catalogue_id;        
            }

			switch($_REQUEST['case']) {
				case "favourite":					
					$model -> favourite = 1;
					$model -> status = 1;
					$model -> favourite_on = time();
					$model -> save(false);
					echo 1;
					break;
				case "location":
					$model -> location = $lid;
					$model -> status = 1;
					$model -> location_on = time();
					$model -> save(false);
					echo 1;
					break;
				case "business":
					$model -> business = 1;
					$model -> status = 1;
					$model -> business_on = time();
					$model -> save(false);
					echo 1;
					break;
				case "unfavourite":
				    $model -> favourite = 0;
					$model -> status = 1;
					$model -> business_on = time();
					$model -> save(false);
					echo 1;
					break;
				default:
					break;
			}
        } else if($case == "favourite") {
            echo 0;
        } else {
            echo 1;
        }
    }
	
    /**
     * Reviewed by _uJJwAL_
     */
	public function actionCityArea()
	{
	    if(!empty($_POST['city_id'])) {
            $_cities = Cities::model()->findByAttributes(array('id' => $_POST['city_id']));
            $params = array('city' => $_cities -> name, 'city_id' => $_cities -> id,'is_city' => 1); 
	    $city=json_encode($params);
            
	    unset(Yii::app()->request->cookies['city']);
            $cookie=new CHttpCookie('city',$city);
            $cookie->expire=time()+60*60*24*180;
	    $cookie->path="/";
            Yii::app()->request->cookies['city']=$cookie;
	    
            
                 
            #setcookie('city', $city, time() + (86400 * 30),'/');;
            
        } else if(!empty($_POST['region_id'])) {
            $_cities = Regions::model()->findByAttributes(array('region' => $_POST['region_id']));
            $params = array('city' => $_cities -> name, 'city_id' => $_cities -> id, 'is_city' => 0);
	    $city=json_encode($params);

	    unset(Yii::app()->request->cookies['city']);
            $cookie=new CHttpCookie('city',$city);
            $cookie->expire=time()+60*60*24*180;
	    $cookie->path="/";
            Yii::app()->request->cookies['city']=$cookie;
            
	    #setcookie('city', $city, time() + (86400 * 30),'/');
        } 
		
		if(isset($_REQUEST['redirectUrl'])) {
            $this-> redirect($_REQUEST['redirectUrl']);
		} else {
			$this -> redirect(array("site/index"));
        }
	}
}
