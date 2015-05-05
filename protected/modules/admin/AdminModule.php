<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
  
		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
                        $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
			// this method is called before any module controller action is performed
			if($url!='admin/user/login'){
                            if(!Yii::app()->user->isGuest){
                                if(!property_exists(Yii::app()->user, 'email')){
                                    $uid = Yii::app()->user->getId();
                                    $user = Users::model()->findByPk($uid);
                                    Yii::app()->user->setState('name', $user->first_name . ' ' . $user->last_name);
                                    Yii::app()->user->setState('user_type', $user->user_type);
                                    Yii::app()->user->setState('email', $user->email);
                                }
                                if(Yii::app()->user->user_type!="admin"){
                                    setcookie('cookie', serialize($cookies), time()-3600);
                                    Yii::app()->user->logout();
                                    Yii::app()->controller->redirect(Yii::app()->createUrl("/admin/user/login"));
                                }
                            }else{
                                Yii::app()->controller->redirect(Yii::app()->createUrl("admin/user/login"));
                            }
                        }
			return true;
		}
		else
			return false;
	}
}
