<?php

class CountriesController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','tree', 'dynamicdata'),
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
		$model=new Countries;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Countries']))
		{
			$model->name = trim($_POST['Countries']['name']);
            $model->code = trim($_POST['Countries']['code']);
            if(!empty($_POST['Countries']['order'])) { //insert order only if it is entered otherwise default will be there
                $model->order = trim($_POST['Countries']['order']);
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Countries']))
		{
			$model->name = trim($_POST['Countries']['name']);
            $model->code = trim($_POST['Countries']['code']);
            if(!empty($_POST['Countries']['order'])) { //insert order only if it is entered otherwise default will remain
                $model->order = trim($_POST['Countries']['order']);
            }

			if($model->update()) //saving data
				$this->redirect(array('view','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('Countries');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Countries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Countries']))
                {
			$model->attributes=$_GET['Countries'];
                        
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
		$model=Countries::model()->findByPk($id);
                if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
    /**
     * Display Tree Structure
     */ 
    public function actionTree()
	{
        $countryCriteria = new CDbCriteria;
        $countryCriteria->select = 't.code, t.name';
        $countryCriteria->order = 't.order desc, t.name ASC';
        $country = Countries::model()->findAll($countryCriteria);
        $this->render('tree',array(
			'dataTree'=>$country,
		));                
	}
       
    /**
     * Dynamic Data for tree
     */ 
    public function actionDynamicdata() {
        if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
            if ($_POST['model'] == 'region') {
                $regionCriteria = new CDbCriteria;
                $regionCriteria->select = 't.id, t.name, t.country'; //select fields
                $regionCriteria->order = 't.order desc, t.name ASC'; //orderby fields
                $regionCriteria->condition = 't.country = :code'; //where condition
                $regionCriteria->params = array(':code'=>$_POST['id']); //passing values to where condtion
                $data = Regions::model()->findAll($regionCriteria); //finding all regions depending upon above criteria
                
                $ul = $this -> renderPartial('_dynamicdata', array('data' => $data, 'case' => 'region', 'id' => $_POST['id']), true);
                $id = "country_".$_POST['id']; //li id which has to be update
            } elseif ($_POST['model'] == 'city') {                
                $value = explode("-", $_POST['id']); //string to array using explode 
                $region = $value[0];
                $code = $value[1];
                
                $cityCriteria = new CDbCriteria;
                $cityCriteria->select = 't.id, t.name'; //select fields
                $cityCriteria->order = 't.order desc, t.name ASC'; //orderby fields
                $cityCriteria->condition = 't.country = :country AND t.region = :region'; //where condition
                $cityCriteria->params = array(':country'=>$code, ':region' => $region); //passing values to where condtion
                $data = Cities::model()->findAll($cityCriteria); //finding all cities depending upon above criteria

                $ul = $this -> renderPartial('_dynamicdata', array('data' => $data, 'case' => 'city'), true);
                $id = "region_".$region; //li id which has to be update
                
            } elseif ($_POST['model'] == 'neighbourhood') {
                $city_id = $_POST['id'];
                $data = Neighborhoods::model()->findAllByAttributes(array("city_id" => $city_id)); //finding all neighbourhoods depending upon attribute values
                
                $ul = $this -> renderPartial('_dynamicdata', array('data' => $data, 'case' => 'neighbourhood'), true);
                $id = "city_".$city_id; //li id which has to be update
            }
            
            $result = array('id' => $id, 'res' => $ul);
            echo json_encode($result);
        }
    }

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='countries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
