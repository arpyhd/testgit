<?php

class ProductsController extends Controller
{
	public $catalogue;

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main-yiibootstrap';

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
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * @author _uJJwAL_
	 * Add Product
	 */ 
	public function actionCreate()
	{
		$model = new Products;
		$model2 = new ProductDetail;

		$tags = array();		
		$categoryList = Categories::model()->findCategoryListByCatalogue($this -> business);
		$catalogues = Catalogue::model()->findCatalogueByBusiness($this -> business);

        $business = Business::model()->findByPk($this->business);
        $currSymbol = Currency::getCurrencySymbolById($business->currency);

		if(Yii::app()->request->isAjaxRequest) {
			$category = $_POST['category'];
			$category = array_values(array_filter(explode(',', $category)));
			$category = implode($category,'');
			$categoryCatalogue = CategoryCatalogue::model()->findCatalogueByCategory($category);

			if(isset($_POST['Products'])) {			
				$model -> attributes = $_POST['Products'];				
				
				$catalogue = $_POST['catalog'];
				$catSplit = array_values(array_filter(explode(',', $catalogue)));

				$tags = trim($_POST['cTags']);
				
				if(!empty($catSplit)) {
					$model -> catalogue_id = $catSplit[0];

					if($model->validate()) { 
						foreach($catSplit as $catalog) {
							$model = new Products;
							$model -> attributes = $_POST['Products'];							
							$model -> catalogue_id = $catalog;

							if($model->save()) {								
								$productTag = new ProductTags;
								$productTag -> product_id = $model -> id;
								$productTag -> tags = $tags;
								$productTag -> save();	
							}	
						}

						$productList = Products::model()->findProductByBusiness($this -> business);
						$this -> renderPartial('/catalogue/_catalogue', array('productList' => $productList));	
					} else {
						echo CActiveForm::validate($model);
					}
				} else {
					echo CActiveForm::validate($model);
				}

				exit;		
			}

			$this->renderPartial('create',array('model'=>$model, 'model2' => $model2, 'tags' => $tags, 'category' => $category,
                                    'catalogues' => $catalogues, 'categoryList' => $categoryList, 'categoryCatalogue' => $categoryCatalogue,
                                    'currSymbol' => $currSymbol));
		}				
	}

	/** 
	 *
	 */
	public function actionAdd() {
		$utility = new UtilityFunction;
		$model = new Products;
		$model2 = new ProductDetail;
		$catalogues = Catalogue::model()->findCatalogueByBusiness($this -> business);

		if(isset($_POST['Products'])) {			
				$model -> attributes = $_POST['Products'];	
				$model -> name = $utility -> wordFilter($model -> name);			
				
				$catalogue = $_POST['catalog'];
				$catSplit = array_values(array_filter(explode(',', $catalogue)));

				$category = $_POST['category'];				
				$tags = trim($_POST['cTags']);
				
				$categoryCatalogue = CategoryCatalogue::model()->findCatalogueByCategory($category);

				if(!empty($catSplit)) {
					if($model->validate()) { 
						if($model -> save()) {
							foreach($catSplit as $catalog) {
								if(!in_array($catalog, $categoryCatalogue)) {
									$catCatalogue = new CategoryCatalogue;
									$catCatalogue -> category_id = $category;
									$catCatalogue -> catalogue_id = $catalog;
									$catCatalogue -> save();
								}

								$model2 = new ProductDetail;

								$model2 -> attributes = $_POST['ProductDetail'];	
								$model2 -> description = $utility -> wordFilter($model2 -> description);						
								$model2 -> catalogue_id = $catalog;
								$model2 -> category_id = $category;
								$model2 -> product_id = $model -> id;
								$model2 -> tags = $tags;

								$model2 -> save();
							}
						}

						$category = array_values(array_filter(explode(',', $category)));
						$productList = Products::model()->findProductByBusiness($this -> business, $catSplit, $category);
						$categoryCatalogue = CategoryCatalogue::model()->findCatalogueByCategory(implode($category,''));	
						$categoryList = Categories::model()->findCategoryListByCatalogue($this->business, $catSplit);		
									
						$categoryString = $this->renderPartial('/catalogue/_category', array('categories' => $categoryList), true);
						$catalogueString = $this -> renderPartial('/catalogue/_catalogue', array('productList' => $productList, 'businessId' => $this->business), true);
						$createString = $this -> renderPartial('create', array('model' => new Products, 'model2' => new ProductDetail, 'category' => implode($category,''), 'catalogues' => $catalogues, 'categoryCatalogue' => $categoryCatalogue), true);

						$return['catalogue'] = $catalogueString;
						$return['category'] = $categoryString;
						$return['create'] = $createString;

						echo json_encode($return);
					} else {
						echo CActiveForm::validate($model);
					}
				} else {
					echo CActiveForm::validate($model);
				}

				exit;		
			}
	}

	/**
	 * @author _uJJwAL_
	 * Update Product
	 */
	public function actionUpdate($id)
	{
		$utility = new UtilityFunction;
		$model = $this -> loadModel($id);
		$catalogueList  = array_values(array_filter(explode(',',urldecode($_GET['catalogue']))));
		$tags = trim($_POST['cTags']);

		$model2 = ProductDetail::model()->findProductDetailByCatalogue($id, $catalogueList);

		$categoryList = Categories::model()->findCategoryListByCatalogue($this -> business);
		$catalogues = Catalogue::model()->findCatalogueByBusiness($this -> business);
		//$catalogues = Catalogue::model()->findCatalogueWithProduct($this -> business, $catalogueList, $model -> name);

		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['Products'])) {
				$model -> attributes = $_POST['Products'];
				$model -> name = $utility -> wordFilter($model -> name);	

				$catalogue = $_POST['catalog'];
				$catSplit = array_values(array_filter(explode(',', $catalogue)));

				if(!empty($catSplit)) {
					if($model->validate()) { 
						if($model -> save()) {
							/*ProductDetail::model()->deleteCatalogueByProduct($id);*/
							foreach($catSplit as $catalog) {

								$model2 = ProductDetail::model()->findByAttributes(array('product_id' => $model ->id, 'catalogue_id' => $catalog));
								if(empty($model2)) {
									$model2 = new ProductDetail;
								}

								$model2 -> attributes = $_POST['ProductDetail'];
								$category = $model2 -> category_id;							
								$model2 -> catalogue_id = $catalog;								
								$model2 -> product_id = $id;
								$model2 -> tags = $tags;

								$model2 -> save();

								$categoryCatalogue = CategoryCatalogue::model()->findCatalogueByCategory($model2 -> category_id);
								if(!in_array($catalog, $categoryCatalogue)) {
									$catCatalogue = new CategoryCatalogue;
									$catCatalogue -> category_id = $category;
									$catCatalogue -> catalogue_id = $catalog;
									$catCatalogue -> save();
								}								
							}
						} 

						$productList = Products::model()->findProductByBusiness($this -> business, $catSplit, array($category));
						$this -> renderPartial('/catalogue/_catalogue', array('productList' => $productList));	
					} else {
						echo CActiveForm::validate($model);
					}
				} else {
					echo CActiveForm::validate($model);
				}

				exit;		
			}

			$this->renderPartial('_create',array('model'=>$model, 'model2' => $model2, 'tags' => $tags, 'catalogues' => $catalogues, 'categoryList' => $categoryList, 'catalogueList' => $catalogueList));
		}				
	}

	/**
	 * @author _uJJwAL_
	 * Disable Product
	 */
	public function actionDelete($id) {
		$catalogues = $_POST['catalogue'];
		$categories = $_POST['category'];

		$catSplit = array_values(array_filter(explode(',', $catalogues)));
		$categories = array_values(array_filter(explode(',', $categories)));

		$catalogueList  = array_values(array_filter(explode(',',urldecode($_GET['catalogue']))));

		foreach($catalogueList as $catalog) {
			$model = ProductDetail::model()->findByAttributes(array('product_id' => $id, 'catalogue_id' => $catalog));
			$model -> delete();
		}

		$productDetail = ProductDetail::model()->findByAttributes(array('product_id' => $id));
		if(empty($productDetail)) {
			$product = $this -> loadModel($id);
			$product -> delete();
		}

		$productList = Products::model()->findProductByBusiness($this -> business, $catSplit, $categories);
		$this -> renderPartial('/catalogue/_catalogue', array('productList' => $productList));	
	}

	public function actionDeleteAll() {
		if(isset($_POST)) {
			$products = $_POST['products'];
			$products = array_filter(explode(',', $products));

			foreach($products as $product) {
				$model = Products::model()->findByPk($product);
				$model -> delete();
			}

			$catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
	        $catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
	        $categoryArray = isset(Yii::app()->session['categories']) ? Yii::app()->session['categories'] : "";

	        if (!is_array($categoryArray)) {
	            $categoryArray = array_values(array_filter(explode(',', $categoryArray)));
	        }

	        if (!is_array($catalogueArray)) {
	            $catalogueArray = array_values(array_filter(explode(',', $catalogueArray)));
	        }

	        $productList = Products::model()->findProductByBusiness($this->business, $catalogueArray, $categoryArray);

	        $this->renderPartial('/catalogue/_catalogue', array('productList' => $productList));
		}
	}

	public function actionMoveCategory() {
		$model = new ProductDetail;

		if(isset($_GET)) {
			$products = $_GET['products'];
			$products = array_filter(explode(',', $products));

			$productDetail = array();
			foreach($products as $product) {
				$productName = Products::model()->findByPk($product) -> name;
				$productDetail[$product] = $productName;
			}

			$categoryList = Categories::model()->findCategoryListByCatalogue($this->business, null);
		}

		if(Yii::app()->request->isAjaxRequest) {

			if(isset($_POST['ProductDetail'])) {
				$model -> attributes = $_POST['ProductDetail'];
				$categoryID = $model -> category_id;

				$products = $_POST['products'];
				$products = array_filter(explode(',', $products));

				if(!empty($products)) {
					foreach($products as $product) {
						$model = ProductDetail::model()->findByAttributes(array('product_id' => $product));
						$model -> category_id = $categoryID;
						$model -> save();
					}	
				} else {
					echo CActiveForm::validate($model);
				} 

				$catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
		        $catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
		        $categoryArray = isset(Yii::app()->session['categories']) ? Yii::app()->session['categories'] : "";

		        if (!is_array($categoryArray)) {
		            $categoryArray = array_values(array_filter(explode(',', $categoryArray)));
		        }

		        if (!is_array($catalogueArray)) {
		            $catalogueArray = array_values(array_filter(explode(',', $catalogueArray)));
		        }

		        $productList = Products::model()->findProductByBusiness($this->business, $catalogueArray, $categoryArray);
		        $this->renderPartial('/catalogue/_catalogue', array('productList' => $productList));

				exit;
			}		
		}

		$this -> renderPartial('_move', array('model' => $model, 'products' => $productDetail, 'categories' => $categoryList));
	}

	/**
	 * @author _uJJwAL_
	 * Load Product
	 */
	public function loadModel($id)
	{
		$model=Products::model()->findByPk($id);
		if($model===null)
		throw new CHttpException(404,'The requested page does not exist.');
		return $model;

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * @author _uJJwAL_
	 * Add Tags
	 */
	public function actionAddTags() {
		$model = new ProductDetail;

		if(isset($_GET)) {
			$products = $_GET['products'];
			$products = array_filter(explode(',', $products));

			$productDetail = array();
			foreach($products as $product) {
				$productName = Products::model()->findByPk($product) -> name;
				$productDetail[$product] = $productName;
			}
		}

		if(Yii::app()->request->isAjaxRequest) {

			if(isset($_POST['ProductDetail'])) {
				$model -> attributes = $_POST['ProductDetail'];
				$products = $_POST['products'];
				$products = array_filter(explode(',', $products));

				if(!empty($products)) {
					foreach($products as $product) {
						$model = ProductDetail::model()->findByAttributes(array('product_id' => $product));
						
						if(!empty($model -> tags)) {
							$model -> tags = $model -> tags . "," . trim($_POST['ProductDetail']['tags']);
						} else {
							$model -> tags = $_POST['ProductDetail']['tags'];
						}	

						$model -> save();
					}	
				} else {
					echo CActiveForm::validate($model);
				} 

				exit;
			}			
		}

		$this -> renderPartial('_tags', array('model' => $model, 'products' => $productDetail));
	}

	public function actionSearch() {
		$product = $_POST['product'];

    	$productList = Products::model()->searchProduct($this -> business, null, null, $product);
    	$categoryList = Categories::model() -> findCategoryListByCatalogue($this -> business, null);

    	$categoryString = $this -> renderPartial('/catalogue/_category', array('categories' => $categoryList), true);
    	$productListString = $this -> renderPartial('/catalogue/_catalogue', array('productList' => $productList), true);

    	$return['categories'] = $categoryString;
        $return['product'] = $productListString;

        echo json_encode($return);
	}
}