<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	public $business = null;
    public $title = null;//to be displayed on top of every page in business section
    public $subtitle = null;
    public $headerLinks = array();//format 'http://www.url.com' => 'name'
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function __construct($id,$module=null)
	{
                parent::__construct($id, $module=null);

                $url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
			// this method is called before any module controller action is performed
			if($url!='admin/user/login' && $url!='site/login'){
                            if(!Yii::app()->user->isGuest){
                                if(!property_exists(Yii::app()->user, 'email')){
                                    $uid = Yii::app()->user->getId();
                                    $user = Users::model()->findByPk($uid);
                                    Yii::app()->user->setState('name', $user->first_name . ' ' . $user->last_name);
                                    Yii::app()->user->setState('user_type', $user->user_type);
                                    Yii::app()->user->setState('email', $user->email);
                                }
                            }
                        }
		

		if(isset(Yii::app()->user->id)) {			
			$business = Business::model()->find(array('condition' => 'user_id = '.Yii::app()->user->getId()));
			
			if($business != null) {
				$this -> business = $business -> id;				
				Yii::app()->session['business']=$business -> id;				
			}
		}
	}	
}
