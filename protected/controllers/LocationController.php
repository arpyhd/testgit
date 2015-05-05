<?php

class LocationController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
	  public $layout = '//layouts/internal-yiibootstrap-2';
#         public $layout = '//layouts/main-yiibootstrap';
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'list', 'dynamicdata', 'search'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $locationModel = new Locations;
        if (isset($_POST['Locations'])) {
            $locationModel -> attributes    = $_POST['Locations'];
            $locationModel -> business_id   = $this -> business;
            $locationModel -> city_id       = empty($locationModel -> city_id) ? NULL : $locationModel -> city_id;

            if($locationModel->validate()) {
                $catalogueModel = new Catalogue;
                $catalogueModel -> catalogue_id = Catalogue::model()->findNextCatalogueIdByBusiness($this -> business);
                $catalogueModel -> business_id  = $this -> business;
                $catalogueModel -> name         = $locationModel -> name;
                
                if($catalogueModel -> save()) {
                    $locationModel -> catalogue_id = $catalogueModel -> id;
                    if ($locationModel -> save()) {
                        if($_POST['continue']) {
                            $this->redirect($this->createUrl('create', array('id' => $_GET['id'])));
                        } else {
                            $this->redirect(array('admin'));
                        }
                    }
                }
            }
        }

        $this -> title      = Yii::t('location', 'Business Location(s)');
        $this -> subtitle   = 'Text.';
        $this -> render('create', array('model' => $locationModel));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Locations'])) {
            $model->attributes = $_POST['Locations'];
            if ($model->save()) {
                if ($_POST['continue']) {
                    $this->redirect($this->createUrl('create', array('id' => $_GET['id'])));
                } else {
                    $this->redirect(array('admin'));
                }
            }
        }

        $this->render('update', array('model' => $model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Locations');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Locations('search');
        $model->unsetAttributes();
        $model->business_id = $this->business;

        if(Yii::app()->request->isAjaxRequest) {
            if(isset($_GET['ajax']) && $_GET['ajax'] == 'location-grid') {
                $catalogue = Catalogue::findCatalogueIdByCatalogueCounterId($_GET['catalogue'], $this->business);
                $model -> catalogue_id = $catalogue -> id;
                $this -> layout = false;
                $this->renderPartial('_gridView', array('model' => $model));
            }

            Yii::app()->end();
        }

        $this -> title = Yii::t('location', "Business Location(s)");
        $this -> subtitle = "Text.";

        if (isset($_GET['Location']))
            $model->attributes = $_GET['Location'];

        $this->render('admin', array('model' => $model));
    }

    /**
     * Output data for populating dynamic lists based on model type
     * @author Andrey Lola <andrey.lola.w@gmail.com>
     * @return void
     * Reviewed by _uJJwAL_
     */
    public function actionDynamicdata() {
        if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
            $condition = array(':code' => $_POST['id']);

            if ($_POST['model'] == 'region') {                
                $code = $_POST['id'];
                
                $regionCriteria = new CDbCriteria;
                $regionCriteria -> select       = 't.*'; //select fields
                $regionCriteria -> order        = 't.order DESC'; //orderby fields
                $regionCriteria -> condition    = 't.country = :code'; //where condition
                
                //if id is numeric it means it should be the primary key of country table
                if (is_numeric($_POST['id'])) {
                    $id         = $_POST['id'];
                    $country    = Countries::model()->findByPk($id); //finding the country code by pk
                    $code       = $country->code;
                }

                $regionCriteria -> params = array(':code' => $code); //passing values to where condtion
                $data = Regions::model()->findAll($regionCriteria); //finding all regions depending upon above criteria
                $data = CHtml::listData($data, "id", function($model) {
                            return Yii::t('region', trim($model->name));
                        });

                $prompt = Yii::t('site', 'Select Region');
            } elseif ($_POST['model'] == 'city') {
                $code   = $_POST['country_id'];
                $region = $_POST['id'];
                
                if (is_numeric($_POST['country_id'])) {
                    $id         = $_POST['country_id'];
                    $country    = Countries::model()->findByPk($id);
                    $code       = $country->code;
                }
                
                $cityCriteria = new CDbCriteria;
                $cityCriteria -> select     = 't.*'; //select fields
                $cityCriteria -> order      = 't.order DESC'; //orderby fields
                $cityCriteria -> condition  = 't.country = :country AND t.region = :region'; //where condition
                $cityCriteria -> params     = array(':country' => $code, ':region' => $region); //passing values to where condtion
                
                $data = Cities::model()->findAll($cityCriteria); //finding all cities depending upon above criteria
                $data = CHtml::listData($data, "id", function($data) {
                            return Yii::t('cities', trim($data->name));
                        });

                $prompt = Yii::t('site', 'Select City');
            } elseif ($_POST['model'] == 'neighborhood') {
                $city_id = $_POST['id'];
                if(!empty($city_id)) {
                    $data = Neighborhoods::model()->findAllByAttributes(array('city_id' => $city_id),array('order' => '`order` DESC'));
                    $data = CHtml::listData($data, 'id', function($data) {
                                return Yii::t('neighborhoods', trim($data->name));
                           });
                }

                $prompt = Yii::t('site', 'Select Neighborhood');
            }

            echo CHtml::tag('option', array('value' => ''), CHtml::encode($prompt), true);
            if(!empty($data)) {
                foreach ($data as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), true); //putting data in <option> tag
                }
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Locations::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'location-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Reviewed by _uJJwAL_
     */

/*Action List
Type of action :
1-implicit cookie[city] not set
2-implicit cookie[city] set
3-post with post variabiles
4-ajax
*/




    public function actionList() {

	if (Yii::app()->request->isPostRequest) {
	    if(Yii::app()->request->isAjaxRequest) {
                    $appRequest=Yii::app()->request;
	            $data=$appRequest->getParam('data','');
		    $is_city=$appRequest->getParam('is_city','');
		    $businessNameSearch=$appRequest->getParam('searchloc','');
		    $categories=json_decode(json_decode($data,true)["categories"],true);
		    $cityid=CJSON::decode($data)["cityid"];
		    $neighbours=json_decode(json_decode($data,true)["neighbours"],true);
		    $page=$appRequest->getParam('page','');
                    #echo $page;
		    #Yii::app()->end();
		    $criteria=new CDbCriteria;
		    $criteria->select='sum(favourite) as nr , l.geo_longitude as geo_longitude,l.geo_latitude as l.geo_latitude,l.name as name,l.address as address,l.business_id as business_id,l.catalogue_id as catalogue_id,l.neighborhood_id as neighborhood_id';
		    $criteria->alias='l';
		    $criteria->join='left join favourite as f on l.id=f.location';
	            #echo count($categories);
		    if (count($categories)!=0) {
			if(count($neighbours)!=0) {
        		    $criteria->condition='l.city_id=:c and business.business_name LIKE :match';
        		    $criteria->params=array(':c'=>$cityid,':match'=>"%$businessNameSearch%");
                            $criteria->addInCondition('business.business_cat_id',$categories);
			    $criteria->addInCondition('l.neighborhood_id',$neighbours);
			} 
			else 
			{
			    $criteria->condition='l.city_id=:c  and business.business_name LIKE :match';
			    $criteria->params=array(':c'=>$cityid,':match'=>"%$businessNameSearch%");
	    		    $criteria->addInCondition('business.business_cat_id',$categories);
			}
        	    }
        	    else 
		    {
			if(count($neighbours)!=0) {
        		    $criteria->condition='l.city_id=:c and business.business_name LIKE :match';
        		    $criteria->params=array(':c'=>$cityid,':match'=>"%$businessNameSearch%");	    
			}
			 else 
			{
			    $criteria->condition='l.city_id=:c and business.business_name like :match';
        		    $criteria->params=array(':c'=>$cityid,':match'=>"%$businessNameSearch%");
    	    		}
    		    }
		    $criteria->group='l.id';
		    $criteria->order='l.name DESC';
		    $criteria->order='nr DESC';
		    $count=Locations::model()->with('business','business.businessCat')->count($criteria);
		    $_GET['page']=$page;
        	    $pages=new CPagination($count);
        	    $pages->pageSize=10;
        	    $pages->applyLimit($criteria);
		    $result=Locations::model()->with('business','business.businessCat')->findAll($criteria);
		    $params['page']  = $page;
		    $params['pages']  = $pages;
        	    $params['result'] = $result;
                    $params['search']= $businessNameSearch;
		    $params['count']=$count;
                    $params['categories']=$categories;
		    $params['neighbours']=$neighbours;
        	    $this->renderPartial('_searchResult',$params);
		    Yii::app()->end();
	    }
	    
	    
            if (!empty($_POST['city_id'])) {
                $_cities = Cities::model()->findByAttributes(array('id' => $_POST['city_id']));
                $params = array('city' => $_cities->name, 'city_id' => $_cities->id, 'is_city' => 1);
		$city = CJSON::encode($params);
        	$cookie=new CHttpCookie('city',$city);
        	$cookie->expire=time()+60*60*24*180;
	   	$cookie->path="/";
        	Yii::app()->request->cookies['city']=$cookie;


            } else if (!empty($_POST['region_id'])) {
                $_cities = Regions::model()->findByAttributes(array('region' => $_POST['region_id']));
                $params = array('city' => $_cities->name, 'city_id' => $_cities->id, 'is_city' => 0);
		$city = CJSON::encode($params);
        	$cookie=new CHttpCookie('city',$city);
        	$cookie->expire=time()+60*60*24*180;
	   	$cookie->path="/";
        	Yii::app()->request->cookies['city']=$cookie;
            }
	    
        }	
	    $_city=CJSON::decode(Yii::app()->request->cookies['city']->value);
            if(isset($_city["city_id"])) {
	    $appRequest=Yii::app()->request;
	    $businessNameSearch=$appRequest->getParam('searchloc','');
	    $criteria=new CDbCriteria;
	    $criteria->select='sum(favourite) as nr , l.geo_longitude as geo_longitude,l.geo_latitude as l.geo_latitude,l.name as name,l.address as address,l.business_id as business_id,l.catalogue_id as catalogue_id';
	    $criteria->alias='l';
	    $criteria->join='left join favourite as f on l.id=f.location';
	    $criteria->condition='l.city_id=:c and business.business_name LIKE :match';
            $criteria->params=array(':c'=>$_city["city_id"],':match'=>"%$businessNameSearch%" );
	    $criteria->group='l.id';
	    $criteria->order=' nr DESC';
	    $count=Locations::model()->with('business','business.businessCat')->count($criteria);
#	    echo "Count=".$count;
#	    echo "Page=".$page;
	    $page=$_GET['page'];
#	    Yii::app()->end();
            $pages=new CPagination($count);
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
	    $result=Locations::model()->with('business','business.businessCat')->findAll($criteria);
	    $params['page']  = $page;
	    $params['pages']  = $pages;
            $params['result'] = $result;
	    $params['count'] = $count;
	    $model['search'] = $businessNameSearch;
	    $model['count']=$count;
            $model['searchView']=$this->renderPartial('_searchResult',$params,true);
            }
	    $this->render('list',$model);
	    
    }

    public function actionSearch() {
        
    }

}
