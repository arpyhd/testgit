<?php

/**
 * This is the model class for table "catalogue".
 *
 * The followings are the available columns in table 'catalogue':
 * @property integer $id
 * @property integer $catalogue_id
 * @property string $name
 * @property integer $business_id
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Business $business
 * @property Categories[] $categories
 * @property Locations[] $locations
 * @property ProductDetail[] $productDetails
 * @property ProductsRejectedImport[] $productsRejectedImports
 */
class Catalogue extends CActiveRecord
{
    
        var $business_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Catalogue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catalogue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, business_id', 'required'),
			array('catalogue_id, business_id, disabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, catalogue_id, name, business_id,last_modified,published,disabled', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'business' => array(self::BELONGS_TO, 'Business', 'business_id'),
            'categories' => array(self::MANY_MANY, 'Categories', 'category_catalogue(catalogue_id, category_id)'),
            'locations' => array(self::HAS_MANY, 'Locations', 'catalogue_id'),
            'productDetails' => array(self::HAS_MANY, 'ProductDetail', 'catalogue_id'),
            'productsRejectedImports' => array(self::HAS_MANY, 'ProductsRejectedImport', 'catalogue_id'),
            'productCount' => array(self::STAT, 'ProductDetail', 'catalogue_id'),
        ); 
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'catalogue_id' => 'Catalogue ID',
			'name' => 'Name',
			'business_id' => 'Business',
                        'last_modified'=> 'Last publish Time',
                        'published'=> 'Published',
			'disabled' => 'Disabled',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('catalogue_id',$this->catalogue_id);
		$criteria->compare('name',$this->name,true);		
		//$criteria -> compare('business_id',$this->business_id);

		$criteria -> addCondition('disabled is null');

		$criteria -> addCondition('business_id = :business');
		$criteria -> params = array(':business' => $this -> business_id);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));

	}

	public function findCatalogueWithProduct($business, $catalogues, $product){
		$criteria = new CDbCriteria;
		$criteria -> select = 't.*';
		$criteria -> distinct = true;
		$criteria -> condition = 'p.name = :product and t.business_id = :business';
		$criteria -> params = array(':business' => $business, ':product' => $product);
		$criteria -> addInCondition('t.id', $catalogues);
		$criteria -> join = 'inner join products p on p.catalogue_id = t.id';

		return $this -> findAll($criteria);
	}

	public function findCatalogueWithCategory($business, $catalogues, $category){
		$criteria = new CDbCriteria;
		$criteria -> select = 't.*';
		$criteria -> distinct = true;
		$criteria -> condition = 'c.name = :category and t.business_id = :business';
		$criteria -> params = array(':business' => $business, ':category' => $category);
		$criteria -> addInCondition('t.id', $catalogues);
		$criteria -> join = 'inner join categories c on c.catalogue_id = t.id';

		return $this -> findAll($criteria);
	}
        
        public function findLastCataloguePublishTime($businessID){
                $maxAnsTime = Yii::app()->db
                ->createCommand("SELECT last_modified FROM catalogue where business_id= ".$businessID." AND published=1 ORDER BY last_modified DESC LIMIT 1")
                ->queryRow();
               //echo $maxAnsTime['last_modified'];
              // exit;
                if(strlen($maxAnsTime['last_modified']) >0)
                {
                    return $maxAnsTime['last_modified'];
                }
                else {
                    return NULL;
                }
                 
	}
        
	public function findCatalogueByBusiness($businessID){
		$criteria = new CDbCriteria;
		$criteria -> condition = 'business_id = :businessID and disabled is null';
		$criteria -> params = array(':businessID' => $businessID);

		return $this -> findAll($criteria);
	}

	public function findCatalogueByCategories($categories) {
		$criteria = new CDbCriteria;
		$criteria -> select = 't.*';
		$criteria -> distinct = true;
		$criteria -> addCondition('t.disabled is null');
		$criteria -> addInCondition('t.category_id', $categories);

		return $this -> findAll($criteria);
	}
        
     /*
     * Made for a specific case. Retrieves a group of catalogues that have this category and catalogue ids 
     * (in other words filter this group of catalogues by contained category)
     * @category = id of Categories (int)
     * @ids = array of Catalogue ids (int)
     * @return: array of Catalogues
     */

    public function findCatalogueByCategoryAndIds($categoryId, $ids) {
        $criteria = new CDbCriteria;
        $criteria->select = 't.*';
        if ($categoryId != null)
            $criteria->join = " JOIN category_catalogue as c ON t.id = c.catalogue_id";
        /* $criteria->distinct = true; */
        $criteria->addCondition('t.disabled is null');
        if ($categoryId != null)
            $criteria->addCondition('c.category_id = ' . intval($categoryId));
        $criteria->addInCondition('t.id', $ids);

        return Catalogue::model()->findAll($criteria);
    }

    /**
     *
     */
    public function findNextCatalogueIdByBusiness($business) {
    	$criteria = new CDbCriteria;
    	$criteria -> condition = 'business_id = :business';
    	$criteria -> params = array(':business' => $business);
    	$criteria -> order = 'id desc';

    	$model = $this -> find($criteria);

    	if(!empty($model) && !is_null($model)) {
    		return ($model -> catalogue_id + 1);
    	} else {
    		return 1;
    	}
    }

    public static function findCatalogueIdByCatalogueCounterId($catalogueId, $businessId)
    {
        return self::model()->find(
            'catalogue_id=:catalogue_id AND business_id=:business_id',
            array(
                'catalogue_id' => $catalogueId,
                'business_id' => $businessId
            )
        );
    }
	
	
	 /*
     * Search different business catalogue for product 
     * @where = request condition string (string)
	 * @orderby = request orderby string (string)
     * @return: array of products
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	
	public function searchCatalogue($where, $orderby){
		
		$result = Yii::app()->db->createCommand()
				->select('c.id as catalogue_id,c.name as catalogue_name,b.id as bussiness_id,b.business_name,b.info1,b.info2,b.website,b.modified_date as b_updated_date,bc.category as business_category,l.country_id,l.region_id,l.city_id,l.neighborhood_id,l.name as location_name,l.address,l.telephone,l.geo_longitude,l.geo_latitude,l.id as lid,pd.id as pd_id,p.id as product_id,p.name as product_name,pd.price,pd.description,pd.link,pd.tags,ct.name as cat_name, ct.type,fv.userid,fv.favourite,fv.updatedon as fav_updation,(SELECT count(*) FROM favourite WHERE business_id=b.id and catalogue_id=c.id and status=1 and favourite>0) as fav_count, curr.symbol')
				->from($this->tableName().' as c')
				->leftJoin(Business::model()->tableName().' as b','b.id=c.business_id')
				->leftJoin(Favourite::model()->tableName().' as fv','fv.business_id=b.id and fv.catalogue_id=c.id and fv.status=1 and fv.favourite>0')
				->leftJoin(BusinessCategory::model()->tableName().' as bc','bc.id=b.business_cat_id')
				->rightJoin(Locations::model()->tableName().' as l','l.business_id=b.id and l.catalogue_id=c.id')
				->leftJoin(ProductDetail::model()->tableName().' as pd','pd.catalogue_id=c.id')
				->leftJoin(Products::model()->tableName().' as p','p.id=pd.product_id')
				->leftJoin(Categories::model()->tableName().' as ct','pd.category_id=ct.id')
				->leftJoin(Currency::model()->tableName().' as curr','curr.country_id=l.country_id')
				->where($where)
				->order($orderby)
				->queryAll();	
		//echo $result->getText();
		return $result;
	}
	
	
	/*
     * get location details by city id
     * @cityid = city id (int)
     * @return: array
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	public function getLocationByCityId($cityid){

		$result = Yii::app()->db->createCommand()
				->select('c.name as country_name,ct.country as country_code, ct.region as region,r.id as region_id, r.name as region_name,ct.name as city_name,ct.id as city_id')
				->from(Cities::model()->tableName().' as ct')
				->leftJoin(Regions::model()->tableName().' as r','r.id=ct.region')
				->leftJoin(Countries::model()->tableName().' as c', 'c.code=ct.country')
				->where('ct.id ='.$cityid)
				->andWhere('r.country = c.code')
				->group('city_id')
				->order('ct.id')
				->queryRow();

		return $result;
		
	}
	
	/*
     * get location details by region id
     * @cityid = region id (int)
     * @return: array
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	public function getLocationByRegionId($regionid){
		
		$result = Yii::app()->db->createCommand()
				->select('c.name as country_name,r.id as region_id, r.name as region_name, r.region as region, ct.name as city_name,ct.id as city_id')
				->from(Regions::model()->tableName().' as r')
				->leftJoin(Cities::model()->tableName().' as ct', 'r.id=ct.region')
				->leftJoin(Countries::model()->tableName().' as c', 'c.code=r.country')
				->where('r.id ='.$regionid)
				->order('r.id')
				->queryRow();
		
		return $result;
	}
	
	
	/*
     * get catalogue category by catalogue id
     * @catalogue_id = catalogue id (int)
     * @return: array
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	public function getCatalogueCategoryByCatalogueId($catalogue_id){
		
		$result = Yii::app()->db->createCommand()
				->select('c.id,c.name')
				->from(Categories::model()->tableName().' as c')
				->leftJoin(CategoryCatalogue::model()->tableName().' as cc', 'cc.category_id=c.id')
				->where('cc.catalogue_id='.$catalogue_id)
				->order('c.name asc')
				->queryAll();
		
		return $result;
	}
	
	
	/*
     * get catalogue product
     * @bussiness_id = business id (int)
	 * @catalogue_id = catalogue id (int)
	 * @condition = conditons (array)
     * @return: array
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	public function getCatalogueProduct($bussiness_id,$catalogue_id,$condition){
		
		$result = Yii::app()->db->createCommand()
				->select('b.id as business_id,pd.*,p.name as product_name,c.name as catalogue_name,cat.name as cat_name')
				->from(Business::model()->tableName().' as b')
				->leftJoin($this->tableName().' as c', 'c.business_id=b.id and c.business_id = '.$bussiness_id)
				->leftJoin(ProductDetail::model()->tableName().'  as pd', 'pd.catalogue_id=c.id')
				->leftJoin(Products::model()->tableName().' as p', 'p.id=pd.product_id')
				->leftJoin(Categories::model()->tableName().' as cat', 'cat.id=pd.category_id')
				->where('c.business_id = '.$bussiness_id.' AND pd.catalogue_id='.$catalogue_id.' '.$condition)
				->order('p.name asc')
				->queryAll();
		
		return $result;
		
	}
        
        
        
}
