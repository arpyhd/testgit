<?php
class SearchController extends Controller
{
	
    /**
     * @var string index dir as alias path from <b>application.</b>  , default to <b>runtime.search</b>
     */
    public $_indexFiles = 'runtime.search';
    /**
     * (non-PHPdoc)
     * @see CController::init()
     */
    public function init(){
        Yii::import('application.vendors.*');
        require_once('Zend/Search/Lucene.php');
        parent::init(); 
    }
	
	/*
     * @Action: Index
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
    public function actionIndex() {
		
		$_cities="";
		$priceorderby = "";

        if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
            
			Yii::app()->session['searchkey'] = $_REQUEST['search'];
			if(isset($_REQUEST['data'])){
				$postSelection = (array) json_decode($_REQUEST['data']);
			}
			
			// get search result
			$selection = array();
			$condition = array(' 1 = 1');
			
			// search by business category
			if (isset($postSelection['categories']) && $postSelection['categories'] != '') {
				$selection['category'] = json_decode($postSelection['categories']);
				$catIds = implode(',',$selection['category']);
				$condition[] = " b.business_cat_id IN (".$catIds.")";
				
			}
			
			// serach by area
			if ((isset($_REQUEST['cityid']) && $_REQUEST['cityid'] > 0) || (isset($postSelection['cityid']) && $postSelection['cityid'] > 0) ) {
				
				if(isset($postSelection['cityid']) && $postSelection['cityid'] > 0){
					
					$cityid = $postSelection['cityid'];
					
				}else{
					$cityid = $_REQUEST['cityid'];	
				}
				
				if(((isset($_REQUEST['is_city']) && $_REQUEST['is_city'] == 1) && $cityid || (isset($postSelection['cityid']) && $postSelection['cityid'] > 0))){
					
					$_cities = Cities::model()->findByAttributes(array('id' => $cityid));

					$city = json_encode(array('city'=>$_cities->name,'city_id'=>$_cities->id,'is_city'=>1));
					
					unset(Yii::app()->request->cookies['city']);
        			$cookie=new CHttpCookie('city',$city);
        			$cookie->expire=time()+60*60*24*180;
					$cookie->path="/";
        			Yii::app()->request->cookies['city']=$cookie;
					//echo $city;die;
					
					if(!isset($_REQUEST['myfavorite']) || ( isset($_REQUEST['myfavorite']) && $_REQUEST['myfavorite'] != 1)){		
						$condition[] = " l.city_id=".$cityid;
					}
					$selection['cityid'] = $cityid;
					$is_city = 1;
				
					$location_details = Catalogue::model()->getLocationByCityId($cityid);
					
				}else{
					
					if(isset($postSelection['regionid']) && $postSelection['regionid'] > 0){
						$cityid = $postSelection['regionid'];	
					}else{
						$cityid = $_REQUEST['cityid'];	
					}
					$_cities = Regions::model()->findbyPk($cityid);
					
					$city=json_encode(array('city'=>$_cities->name,'city_id'=>$_cities->id,'is_city'=>0));
					unset(Yii::app()->request->cookies['city']);
        			$cookie=new CHttpCookie('city',$city);
        			$cookie->expire=time()+60*60*24*180;
					$cookie->path="/";
        			Yii::app()->request->cookies['city']=$cookie;
					
					if(!isset($postSelection['myfavorite']) || ( isset($postSelection['myfavorite']) && $postSelection['myfavorite'] != 1)){
						$condition[] = " l.region_id=".$cityid;
					}
					$selection['cityid'] = $cityid;
					$selection['regionid'] = $cityid;
					$is_city = 0;
				
					$location_details = Catalogue::model()->getLocationByRegionId($cityid);
					
				}
			}
			
			// search by neighbor
			if(isset($postSelection['neighbours'])){
				$selection['neighbor_id'] = json_decode($postSelection['neighbours']);
				$nidds = implode(',',$selection['neighbor_id']);
				if(!isset($postSelection['myfavorite']) || ( isset($postSelection['myfavorite']) && $postSelection['myfavorite'] != 1)){
					$condition[] = " l.neighborhood_id IN (".$nidds.")";
				}
				
			}
			
			// search by product keyword
			if (isset($_REQUEST['search']) AND $_REQUEST['search'] != "") {
				$explode = explode(' ', $_REQUEST['search']);
				$extra = array();
				$foundinproduct = "";
				$foundindesc = "";
				$foundinboth = "";
				$opt = 0;
				$nextoperator = '';
				
				foreach ((array) $explode as $k=>$xt) {
					if($k > 0 && $opt > 0){
						$operator = 'OR';
					}else{
						$operator = '';
					}
					if(strlen($xt) > 3){
						$foundinproduct .= $operator." p.name LIKE '%" . $xt . "%'";
						$foundindesc .= $operator." pd.description LIKE '%" . $xt . "%'";
						$foundinboth .= $operator." p.name LIKE '%" . $xt . "%' OR pd.description LIKE '%" . $xt . "%'";
						$foundincat .= $operator." ct.name LIKE '%" . $xt . "%' OR pd.tags LIKE '%" . $xt . "%'";
						$nextoperator = ' OR';
						$opt++;
					}
					
				}
				$condition[] = "(".$foundinproduct.$nextoperator." p.name LIKE '%".$_REQUEST['search']."%' OR pd.description LIKE '%".$_REQUEST['search']."%' OR ct.name LIKE '%".$_REQUEST['search']."%' OR pd.tags LIKE '%".$_REQUEST['search']."%' ".$nextoperator.$foundincat.") ";
			}
			
			// search by my favourite location
			if((isset($postSelection['myfavorite']) && $postSelection['myfavorite'] == 1)){
				
				$myfv = $postSelection['myfavorite'];
				$selection['myfavorite'] = $myfv;
				$condition[] = " fv.userid=".Yii::app()->user->id." AND fv.favourite=1 AND fv.catalogue_id=c.id";
			}
			
			// search by price
			if (isset($postSelection['minprice']) && $postSelection['maxprice'] != '') {
				if ($postSelection['minprice'] != '' && $postSelection['maxprice'] == '') {
					$condition[] = " CAST(pd.price as SIGNED) >= " . $postSelection['minprice'] . " ";
				} elseif ($postSelection['minprice'] == '' && $postSelection['maxprice'] != '') {
					$condition[] = " CAST(pd.price as SIGNED) < " . $postSelection['maxprice'] . " ";
				} elseif ($postSelection['minprice'] != '' && $postSelection['maxprice'] != '') {
					$condition[] = " CAST(pd.price as SIGNED) >= " . $postSelection['minprice'] . " AND CAST(pd.price as SIGNED) <" . $postSelection['maxprice'] . " ";
				} else {
					$condition[] = " pd.price != 0 ";
				}
				$priceorderby = " pd.price asc,";
				$selection['minPrice'] = $postSelection['minprice'];
				$selection['maxPrice'] = $postSelection['maxprice'];
			}
			
			// search by type
			if ((isset($postSelection['types']) && $postSelection['types'] != '')) {
				
				$selection['types'] = json_decode($postSelection['types']);
				$typeIds = implode(',',$selection['types']);
				$condition[] = " ct.type IN (".$typeIds.")";
				
			}

			$fCondition = implode(' AND ', $condition);
			$orderby = "";
			$case = "";
			if(Yii::app()->user->id){
				$orderby .= "CASE WHEN (fv.userid=".Yii::app()->user->id." and fv.catalogue_id=c.id and fv.catalogue_id=c.id) THEN fv.favourite END desc,";
				$orderby .= "CASE WHEN (fv.userid=".Yii::app()->user->id." and fv.catalogue_id=c.id and fv.catalogue_id=c.id) THEN unix_timestamp(b_updated_date) END desc,";
			}
			
			$orderby .= "CASE WHEN (fv.catalogue_id=c.id and fav_count > 0) THEN fav_count END desc,
						unix_timestamp(b_updated_date) desc,";
			$orderby .= $priceorderby;
			$orderby .= "(CASE WHEN p.name LIKE '%" . $_REQUEST['search'] . "%' THEN 1 ELSE 0 END) DESC";
			
			if($foundinboth != '')
				$orderby .= ",(CASE WHEN (".$foundinboth.") THEN 1 ELSE 0 END) DESC";
			
			if($foundinproduct != '')
				$orderby .= ",(CASE WHEN (".$foundinproduct.") THEN 1 ELSE 0 END) DESC";
			
			if($foundindesc != '')	
				$orderby .= ",(CASE WHEN (".$foundindesc.") THEN 1 ELSE 0 END) DESC";
				
				
			$result_data = Catalogue::model()->searchCatalogue($fCondition, $orderby);
			
            $data = array();
            $businessArr = array();
			$productArr = array();
			$catalogueArr = array();
			$prdetailArr = array();
            $cnt = 0;
            foreach ((array) $result_data as $row) {
				
				if(in_array($row['pd_id'],$prdetailArr) && in_array($row['product_id'],$productArr) && in_array($row['catalogue_id'],$catalogueArr)){
					continue;
				}
				$_city = Cities::model()->findByAttributes(array('id' => $row['city_id']));
				$_region = Regions::model()->findByAttributes(array('id' => $row['region_id']));
				$_country = Countries::model()->findByAttributes(array('id' => $row['country_id']));
				$neighborname = '';
				if(isset($row['neighborhood_id']) && $row['neighborhood_id'] != ''){
					$_neighbor = Neighborhoods::model()->findByAttributes(array('id' => $row['neighborhood_id']));
					$neighborname = $_neighbor->name;
				}
				
				if(!in_array($row['product_id'],$productArr))
					array_push($productArr, $row['product_id']);
				
				if(!in_array($row['catalogue_id'],$catalogueArr))
					array_push($catalogueArr, $row['catalogue_id']);
					
				if(!in_array($row['pd_id'],$prdetailArr))
					array_push($prdetailArr, $row['pd_id']);
				
				$product_detail = array(
					'product_id' => $row['product_id'],
					'product_name' => $row['product_name'],
					'product_link' => $row['link'],
					'price' => $row['price'],
					'description' => $row['description'],
					'cat_name' => $row['cat_name'],
					'tags'=>$row['tags'],
					'type'=>$row['type']
				);
                $data[$row['catalogue_name']][] = array(
					'rowdata' => $row,
					'product_detail' =>$product_detail,
					'catalog_city' => $_city->name,
					'catalog_region' => $_region->name,
					'catalog_country' => $_country->name,
					'catalog_neighborhood' => $neighborname,
					'currency_symbol'=>$row['symbol']
                );
				
				$price_array[] = str_replace(',','.',$row['price']);
				
                if (!in_array($row['catalogue_name'], $businessArr)) {
                    array_push($businessArr, $row['catalogue_name']);
                }
				$cnt++;
            }
			if(is_array($price_array) && count($price_array) > 0){
				asort($price_array);
				$firstelement = array_slice($price_array, 0, 1);
				$minprice = floor($firstelement[0]);
				$maxprice = round(end($price_array));
			}else{
				$minprice = 0;
				$maxprice = 1000;
			}
			
            $totals = $cnt;
            $total_businesses = count((array) $businessArr);
			

            // paginating
            $pageSize = 10;
            $pages = new CPagination($totals);
            $pages->setPageSize($pageSize);
			$page = (isset($_GET['page']) ? $_GET['page'] : 1);
            // save data in session
            Yii::app()->session['data'] = $data;
			Yii::app()->session['selection'] = $selection;
            Yii::app()->session['totals'] = $totals;
            Yii::app()->session['total_businesses'] = $total_businesses;
            Yii::app()->session['query'] = $_REQUEST['search'];
            Yii::app()->session['pages'] = $pages;
			Yii::app()->session['minprice'] = $minprice;
			Yii::app()->session['maxprice'] = $maxprice;
			
			if($_REQUEST['requestType'] == 'ajax'){
				
				$this->renderPartial('ajaxResult', array(
					'data' => array_slice($data, ($page - 1) * $pageSize, $pageSize),
					'totals' => $totals,
					'total_businesses' => $total_businesses,
					'pages' => $pages,
					'page_size' => $pageSize,
					'query' => $_REQUEST['search']
					)
				);
			}else{
			
				$this->layout = '/layouts/internal-yiibootstrap';
				$this->render('index', array(
					'selection' => serialize($selection),
					'data' => array_slice($data, ($page - 1) * $pageSize, $pageSize),
					'location_details' => $location_details,
					'totals' => $totals,
					'total_businesses' => $total_businesses,
					'query' => $_REQUEST['search'],
					'pages' => $pages,
					'page_size' => $pageSize,
					'is_city'=>$is_city,
					'minprice' =>$minprice,
					'maxprice' =>$maxprice
						)
				);
			}
        } else {
			
			//echo '<pre>';
			//print_r($_REQUEST);
			//echo '</pre>';die;
			if(isset($_REQUEST['selection']) && $_REQUEST['selection'] != ''){
				$postSelection = unserialize($_REQUEST['selection']);
			}
            $pageSize = 10;
			$pages = new CPagination(Yii::app()->session['totals']);
            $pages->setPageSize($pageSize);
			$page = (isset($_GET['page']) ? $_GET['page'] : 1);
            $data = Yii::app()->session['data'];
			$selection = Yii::app()->session['selection'];
			
			if (!empty($_POST['city_id'])) {
				
				$_cities = Cities::model()->findByAttributes(array('id' => $_POST['city_id']));
				$city=json_encode(array('city'=>$_cities->name,'city_id'=>$_cities->id,'is_city'=>1));
				unset(Yii::app()->request->cookies['city']);
				$cookie=new CHttpCookie('city',$city);
				$cookie->expire=time()+60*60*24*180;
				$cookie->path="/";
				Yii::app()->request->cookies['city']=$cookie;
				$city_id =  $_POST['city_id'];
				$is_city = 1;
				$selection['cityid'] = $_REQUEST['city_id'];
				$location_details = Catalogue::model()->getLocationByCityId($city_id);
				
			}else if(!empty($_REQUEST['region_id'])){
				
				$_cities = Regions::model()->findbyPk($_REQUEST['region_id']);
				$city=json_encode(array('city'=>$_cities->name,'city_id'=>$_cities->id,'is_city'=>0));
				unset(Yii::app()->request->cookies['city']);
				$cookie=new CHttpCookie('city',$city);
				$cookie->expire=time()+60*60*24*180;
				$cookie->path="/";
				Yii::app()->request->cookies['city']=$cookie;
				$is_city = 0;
				$city_id =  $_REQUEST['region_id'];
				$selection['cityid'] = $_REQUEST['city_id'];
				$selection['regionid'] = $_REQUEST['region_id'];
				$location_details = Catalogue::model()->getLocationByRegionId($_REQUEST['region_id']);
				
			}else if(isset(Yii::app()->request->cookies['city']->value)) 
			{
    			$_cities=json_decode(Yii::app()->request->cookies['city']->value);			
				$city_id = $_cities->city_id;
				$_cities=(object)(array('name'=>$_cities->city,'id'=>$_cities->city_id,'is_city'=>$_cities->is_city));
				if($_cities->is_city == 1){
					$location_details = Catalogue::model()->getLocationByCityId($city_id);
				}else{
					$location_details = Catalogue::model()->getLocationByRegionId($city_id);
				}
			}
			
			if($_REQUEST['requestType'] == 'ajax'){
				
				$this->renderPartial('ajaxResult', array(
					'data' => (isset($data)) ? array_slice($data, ($page - 1) * $pageSize, $pageSize) : array(),
					'totals' => Yii::app()->session['totals'],
					'total_businesses' => Yii::app()->session['total_businesses'],
					'pages' => $pages,
					'page_size' => $pageSize,
					)
				);
			}else{
				$this->layout = '/layouts/internal-yiibootstrap';
				$this->render('index', array(
					'selection' => serialize($selection),
					'data' => (isset($data)) ? array_slice($data, ($page - 1) * $pageSize, $pageSize) : array(),
					'location_details' => $location_details,
					'totals' => Yii::app()->session['totals'],
					'total_businesses' => Yii::app()->session['total_businesses'],
					'query' => Yii::app()->session['query'],
					'pages' => $pages,
					'page_size' => $pageSize,
					'city' => $_cities->name,
					'city_id'=>$_cities->id,
					'is_city'=>$is_city,
					'minprice' =>Yii::app()->session['minprice'],
					'maxprice' =>Yii::app()->session['maxprice']
					)
				);
			}
        }
    }
    
   public function actionCreate()
    {
	  $index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles), true);
		$sql= "SELECT 
					c.id, 
					c.product_name, 
					c.product_link, 
					c.price, 
					c.modified_date,
					c.type,
					b.business_name,
					t.tag
					
				FROM 
					catalogues c
				LEFT JOIN 
					businesses b 
				ON 
					c.business_id = b.id
				LEFT JOIN
					catalogue_tags t
				ON
					c.id = t.catalogue_id";
		$catalogue = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($catalogue as $cata){
		
            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::Text('id',
                                          CHtml::encode($cata['id']), 'utf-8')
            );
            $doc->addField(Zend_Search_Lucene_Field::Text('product_name',
                                          CHtml::encode($cata['product_name']), 'utf-8')
            );
 
            $doc->addField(Zend_Search_Lucene_Field::Text('product_link',
                                            CHtml::encode($cata['product_link'])
                                                , 'utf-8')
            );   
 
            $doc->addField(Zend_Search_Lucene_Field::Text('price',
                                          CHtml::encode($cata['price'])
                                          , 'utf-8')
            );
			$doc->addField(Zend_Search_Lucene_Field::Text('modified_date',
										  CHtml::encode($cata['modified_date'])
										  , 'utf-8')
			);
			$doc->addField(Zend_Search_Lucene_Field::Text('type',
										  CHtml::encode($cata['type'])
										  , 'utf-8')
			);			
			$doc->addField(Zend_Search_Lucene_Field::Text('business_name',
										  CHtml::encode($cata['business_name'])
										  , 'utf-8')
			);
 			$doc->addField(Zend_Search_Lucene_Field::Text('tag',
										  CHtml::encode($cata['tag'])
										  , 'utf-8')
			);
            $index->addDocument($doc);
        }
        $index->commit();
        echo 'Lucene index created';
    }

	public function actionSearch()
	{
		$this->layout='column2';
		 if (($term = Yii::app()->getRequest()->getParam('q', null)) !== null) {
		
			$index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles));
			$results = $index->find($term);
			$query = Zend_Search_Lucene_Search_QueryParser::parse($term);       
 
			$this->render('search', compact('results', 'term', 'query'));
		}
	}
	
}