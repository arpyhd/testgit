<?php

/**
 * This is the model class for table "business".
 *
 * The followings are the available columns in table 'business':
 * @property integer $id
 * @property string $title
 * @property string $first_name
 * @property string $last_name
 * @property string $business_name
 * @property string $website
 * @property string $info2
 * @property string $info1
 * @property string $business_cat_id
 * @property integer $country_id
 * @property string $currency
 * @property integer $added_by
 * @property string $added_date
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $user_id
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property BusinessCategory $businessCat
 * @property Countries $country
 * @property Catalogue[] $catalogues
 * @property Locations[] $locations
 */
class Business extends CActiveRecord
{    
    public $language;
    public $email;
    public $from_date;
    public $to_date;
    public $bad_words_count;
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Business the static model class
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
		return 'business';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, first_name, last_name, business_name, business_cat_id, info2, country_id, currency', 'required'),
			array('added_by, modified_by, user_id, disabled', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>4),
			array('first_name, last_name', 'length', 'max'=>20),
			array('business_name, website, info2', 'length', 'max'=>50),
			array('info1', 'length', 'max'=>60),
			array('business_cat_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, language, first_name, last_name, business_name, website, info2, info1, added_by, added_date, modified_date, modified_by, user_id, disabled,email,business_cat_id', 'safe', 'on'=>'search'),
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
			'user'      	=> array(self::BELONGS_TO, 'Users', 'user_id'),
            'adder'     	=> array(self::BELONGS_TO, 'Users', 'added_by'),
            'modifier'  	=> array(self::BELONGS_TO, 'Users', 'modified_by'),
			'catalogues'	=> array(self::HAS_MANY, 'Catalogue', 'business_id'),
            'locations' 	=> array(self::HAS_MANY, 'Location', 'business_id'),
            'favourite' 	=> array(self::HAS_MANY, 'Favourite', 'business_id'),
            'businessCat' 	=> array(self::BELONGS_TO, 'BusinessCategory', 'business_cat_id'),
            'country' 		=> array(self::BELONGS_TO, 'Countries', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Business', 'ID'),
			'title' => Yii::t('Business', 'Title'),
			'first_name' => Yii::t('Business', 'Name'),
			'last_name' => Yii::t('Business', 'Surname'),
			'business_name' => Yii::t('Business', 'Business Name'),
			'website' => Yii::t('Business', 'Website'),
			'info2' => Yii::t('Business', 'Business Description'),
			'info1' => 'Info1',
			'added_by' => 'Added By',
			'added_date' => 'Added Date',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'user_id' => 'User',
			'disabled' => 'Disabled',
			'user[email]'=> 'Email',
			'user[password]'=> 'Password',
			'business_cat_id' => Yii::t('BusinessCategory','Business Category'),
            'country_id' => Yii::t('BusinessCountry', 'Business Country'),
            'currency'  => Yii::t('Bussiness', 'Base Currency'),
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

		$criteria=new CDbCriteria;
		$criteria->select = array(
			"*",
			"(select count(0) from catalogue where business_id = t.id) as cataloguesCount",
			"(select count(0) from products left join product_detail on products.id = product_detail.product_id where product_detail.catalogue_id in (select id as catalogue_id from catalogue where business_id = t.id)) as recordsCount",
			"(select count(0) from word_filter_user where user_id = t.user_id) as bad_words_count",
		);

		$criteria->with = array( 'user' );
		
		if((isset($this->from_date) && trim($this->from_date) != "") && (isset($this->to_date) && trim($this->to_date) != ""))
				$criteria->addBetweenCondition('t.added_date', ''.$this->from_date.'', ''.$this->to_date.'');
                
		$criteria->compare('id',$this->id);
        $criteria->compare('user.language', $this->language, true );
        $criteria->compare('user.email', $this->email, true );
		$criteria->compare('title',$this->title,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('t.last_name',$this->last_name,true);
		$criteria->compare('business_name',$this->business_name,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('info2',$this->info2,true);
		$criteria->compare('info1',$this->info1,true);
		$criteria->compare('added_by',$this->added_by);
		$criteria->compare('added_date',$this->added_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('disabled',$this->disabled);

		return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                    	'defaultOrder' => 't.added_date desc',
                        'attributes' => array(
                            'language' => array(
                                'asc' => 'user.language',
                                'desc' => 'user.language DESC',
                            ),
                            'email' => array(
                                'asc' => 'user.email',
                                'desc' => 'user.email DESC',
                            ),
                            'cataloguesCount' => array(
                                'asc' => 'cataloguesCount',
                                'desc' => 'cataloguesCount desc',
                            ),
                            'recordsCount' => array(
                                'asc' => 'recordsCount',
                                'desc' => 'recordsCount desc',
                            ),
                            'bad_words_count' => array(
                                    'asc' => 'bad_words_count',
                                    'desc' => 'bad_words_count DESC',
                                ),
                            '*',
                        ),
                    ),
		));
	}
        
        /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchNew()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = array(
			"*",
			"(select count(0) from catalogue where business_id = t.id) as cataloguesCount",
			"(select count(0) from products left join product_detail on products.id = product_detail.product_id where product_detail.catalogue_id in (select id as catalogue_id from catalogue where business_id = t.id)) as recordsCount",
			"(select count(0) from word_filter_user where user_id = t.user_id) as bad_words_count",
		);
		
		$criteria->with = array( 'user' );
		
		if((isset($this->from_date) && trim($this->from_date) != "") && (isset($this->to_date) && trim($this->to_date) != ""))
				$criteria->addBetweenCondition('t.added_date', ''.$this->from_date.'', ''.$this->to_date.'');
                
		$criteria->compare('id',$this->id);
		$criteria->compare('user.language', $this->language, true );
		$criteria->compare('user.email', $this->email, true );
		$criteria->compare('title',$this->title,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('t.last_name',$this->last_name,true);
		$criteria->compare('business_name',$this->business_name,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('info2',$this->info2,true);
		$criteria->compare('info1',$this->info1,true);
		$criteria->compare('added_by',$this->added_by);
		$criteria->compare('added_date',$this->added_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('disabled',$this->disabled);

		return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder'=>'t.id DESC',
                        'attributes' => array(
                            'language' => array(
                                'asc' => 'user.language',
                                'desc' => 'user.language DESC',
                            ),
                            'email' => array(
                                'asc' => 'user.email',
                                'desc' => 'user.email DESC',
                            ),
                            'cataloguesCount' => array(
                                'asc' => 'cataloguesCount',
                                'desc' => 'cataloguesCount desc',
                            ),
                            'recordsCount' => array(
                                'asc' => 'recordsCount',
                                'desc' => 'recordsCount desc',
                            ),
                            'bad_words_count' => array(
                                    'asc' => 'bad_words_count',
                                    'desc' => 'bad_words_count DESC',
                                ),
                            '*',
                        ),
                    ),
		));
	}
        
        
        /**
	 * Retrieves the catalogue count for a business.
	 * @return integer the number of catalogues a business has
	 */
        public function getCataloguesCount(){
            return count($this->catalogues);
        }
        
        /**
	 * Retrieves the product count for a business.
	 * @return integer the number of products a business has
	 */
        public function getRecordsCount(){
            $sql="select count(0) as recordsCount from products left join product_detail on products.id = product_detail.product_id where catalogue_id in (select id as catalogue_id from catalogue where business_id = '".$this->id."')";
            $connection = Yii::app()->db; 
            $command= $connection->createCommand($sql);
            $data = $command->queryRow();
            return $data['recordsCount'];
        }

	/**
	 * Use Yii's beforeSave function to add the modifier
	 */
	protected function beforeSave() {
		if($this -> isNewRecord) {
			$this -> added_by = Yii::app()->user->getId();
			$this -> added_date = date('Y-m-d H:i:s', time());
		}

		$this -> modified_by = Yii::app()->user->getId();
		$this -> modified_date = date('Y-m-d H:i:s', time());
		
		return true;
	}
        
    /**
     * Retrieves this business' categories
     * @return array of Categories objects, empty array if none found
     */

    public function getCategories() {
        $categories = array();
        foreach ($this->catalogues as $c) {
            $categories = array_merge($categories, $c->categories);
        }
        return $categories;
    }

    public static function getCountryByBusinessId($id)
    {
        $countryId = self::model()->findByPk($id)->country_id;
        return Countries::model()->findByPk($countryId);
    }

    /**
     * @param $cityId
     * @param $isCity
     * @param string $businessName
     * @return mixed
     */
    public static function getLocationForBusiness($cityId, $isCity, $businessName = '', $catId = '')
    {
        $param = array();
        $rs = Yii::app()->db->createCommand()
            ->setFetchMode(PDO::FETCH_OBJ)
            ->select('b.id, b.business_name, b.info2, l.name locationName, l.id locationId')
            ->from('business b, locations l, ')
            ->where(array('and', 'b.id = l.business_id'));
        if ($isCity) {
            $rs->andWhere('l.city_id = :cityId');
            $param[':cityId' ] = $cityId;
        } else if (!$isCity && !empty($cityId)) {
            $rs->andWhere('l.region_id = :cityId');
            $param[':cityId' ] = $cityId;
        } else if(!empty($catId)) {
            $rs->andWhere('b.business_cat_id = :catId');
            $param[':catId'] = $catId;
        }

        $rs->order = 'b.business_name';

        if(!empty($businessName)) {
            $rs->andWhere('b.business_name like :businessName');
            $param[':businessName']     = '%' . $businessName . '%';
        }

        $rs->params = $param;

        return $rs->queryAll();

    }

    public function getArea($cityId, $isCityId)
    {
        $sql = 'SELECT c.name as country_name,r.id as region_id, r.name as region_name,ct.name as city_name,ct.id as city_id FROM cities as ct
						LEFT JOIN regions as r ON (r.id=ct.region)
						LEFT JOIN countries as c ON (c.code=r.country)';
        $where = '';
	    if($isCityId)
            $where = " where ct.id =".$cityId." GROUP BY ct.id";
        else
            $where = " where r.id =".$cityId." GROUP BY r.id";

        $sql .= $where;

        return Yii::app()->db->createCommand($sql)->queryRow();
    }
   
      

}

?>