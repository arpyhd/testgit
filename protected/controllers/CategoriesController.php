<?php

class CategoriesController extends Controller
{
		
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
	 * Reviewed by _uJJwAL_
	 * Add Category
	 */
	public function actionCreate()
	{
		$model = new Categories;
		$catalogues = Catalogue::model()->findCatalogueByBusiness($this -> business);
		$catalogueSelected = Yii::app()->session['catalogues'];

		/* Categories are created using Ajax Request */
		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['Categories']))
			{
				$model -> attributes = $_POST['Categories'];				
				$catalogue = $_POST['catalog'];
				$catSplit = array_values(array_filter(explode(',', $catalogue)));

				if(!empty($catSplit)) {
					if($model->validate()) { 
						//$model -> attributes = $_POST['Categories'];
						if($model->save()) {
							foreach($catSplit as $catalog) {
								$categoryCatalogue = new CategoryCatalogue;
								$categoryCatalogue -> catalogue_id = $catalog;
								$categoryCatalogue -> category_id = $model -> id;
								$categoryCatalogue -> save();						
							}	
						}
					} else {
						echo CActiveForm::validate($model);
					} 					
				} else {
					echo CActiveForm::validate($model);
				} 

				exit;
			}
				
			$this -> renderPartial('_create', array('model' => $model, 'catalogues' => $catalogues, 'catalogueSelected' => $catalogueSelected));
		} 
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		//$catalogueList  = explode(',',urldecode($_GET['catalogue']));
		//$catalogues = Catalogue::model()->findCatalogueWithCategory($this -> business, $catalogueList, $model -> name);
		$catalogues = Catalogue::model()->findCatalogueByBusiness($this -> business);
		$catalogueSelected = CategoryCatalogue::model()->findCatalogueByCategory($id);

		/* Categories are created using Ajax Request */
		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['Categories']))
			{
				$model -> attributes = $_POST['Categories'];				
				$catalogue = $_POST['catalog'];
				$catSplit = array_values(array_filter(explode(',', $catalogue)));

				if(!empty($catSplit)) {
					if($model->validate()) { 
						if($model -> save()) {
							CategoryCatalogue::model()->deleteCatalogueByCategory($id);
							foreach($catSplit as $catalog) {								
								$categoryCatalogue = new CategoryCatalogue;
								$categoryCatalogue -> catalogue_id = $catalog;
								$categoryCatalogue -> category_id = $model -> id;
								$categoryCatalogue -> save();
							}	
						}
					} else {
						echo CActiveForm::validate($model);
					} 					
				} else {
					echo CActiveForm::validate($model);
				} 

				exit;
			}
				
			$this -> renderPartial('_create', array('model' => $model, 'catalogues' => $catalogues, 'catalogueSelected' => $catalogueSelected));
		} 
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$category = $this->loadModel($id);
		$category -> delete();

		CategoryCatalogue::model()->deleteCatalogueByCategory($id);
		$productDetail = ProductDetail::model()->findByAttributes(array('category_id' => $id));
		if(empty($productDetail)) {
			$product = $this -> loadModel($id);
			$product -> delete();
		}

		//$catalogueList  = array_values(array_filter(explode(',',urldecode($_GET['catalogue']))));
		$catalogue = urldecode($_POST['catalogue']);
		if($catalogue == 'catalogueAllCheck') {
			$catalogue = null;
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
		//$this->redirect(Yii::app()->urlManager->createUrl('/categories/admin',array('ajax' => 'true', 'catalogue' => $catalogue)));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Category');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($ajax=null, $catalogue = null)
	{
		$model = new Categories();	
		$model -> unsetAttributes();  // clear any default values
		
		$model -> business = $this -> business;
		if(isset($_GET['Category']))
			$model -> attributes = $_GET['Category'];

		if($ajax) {
			if(!is_null($catalogue)) {
				$catalogue = array_values(array_filter(explode(',',urldecode($catalogue))));
			}

			$categories = Categories::model()->findCategoryListByCatalogue($this->business, $catalogue);
			$this -> renderPartial('/catalogue/_category', array('categories' => $categories));
		} else {
			$this->renderPartial('admin',array('model'=>$model));	
		}	
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Category the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Categories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Category $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionCatalogueCategory() {
    	$catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
		$categoryList = Categories::model()->findCategoryListByCatalogue($this->business, $catalogueArray);
		$this -> renderPartial('/catalogue/_category',array('categories' => $categoryList));
    }

    public function actionSearch() {
    	$category = $_POST['category'];
    	$categoryArray = array();

    	$criteria = new CDbCriteria;
    	$criteria -> compare('name',$category,true);
    	$criteria -> compare('added_by', Yii::app()->user->id);
    	$criteria -> addCondition('disabled is null');

    	$categoryList = Categories::model()->findAll($criteria);

    	foreach($categoryList as $category) {
    		array_push($categoryArray, $category -> id);
    	}

    	$productList = Products::model()->findProductByBusiness($this->business, null, $categoryArray);

    	$categoryString = $this->renderPartial('/catalogue/_category', array('categories' => $categoryList), true);
    	$productListString = $this->renderPartial('/catalogue/_catalogue', array('productList' => $productList), true);

    	$return['categories'] = $categoryString;
        $return['product'] = $productListString;

        echo json_encode($return);
    }
}
