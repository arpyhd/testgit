<?php
class DashboardController extends Controller
{
	public $layout='//layouts/main-yiibootstrap';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 *
	 */	
	public function actionIndex()
	{
		$this->title = Yii::t('dashboard', "Welcome to Business Account");
        $this->subtitle = Yii::t('dashboard', 'Some other text');
       
		$model = new Catalogue;
		$this->render('index', array('model' => $model));
	}
}

?>