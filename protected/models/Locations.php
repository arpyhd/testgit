<?php

/**
 * This is the model class for table "locations".
 *
 * The followings are the available columns in table 'locations':
 * @property integer $id
 * @property integer $business_id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $city_id
 * @property integer $neighborhood_id
 * @property string $name
 * @property string $address
 * @property string $telephone
 * @property string $geo_longitude
 * @property string $geo_latitude
 * @property integer $catalogue_id
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Regions $region
 * @property Business $business
 * @property Countries $country
 * @property Cities $city
 * @property Neighborhoods $neighborhood
 * @property Regions $region
 * @property Catalogue $catalogue
 */
class Locations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Locations the static model class
	 */
    public $nr;
	public $name;
	public $geo_latitude;
    public $geo_longitude;
    public $address;
	public $business_id;
    public $catalogue_id;
	public $neighborhood_id;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('business_id, country_id, name, region_id, address, telephone', 'required'),
			array('business_id, country_id, region_id, neighborhood_id, catalogue_id, disabled', 'numerical', 'integerOnly'=>true),
			array('city_id', 'length', 'max'=>11),
			array('name, address, telephone', 'length', 'max'=>80),
			array('geo_longitude, geo_latitude', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, business_id, country_id, region_id, city_id, neighborhood_id, name, address, telephone, geo_longitude, geo_latitude, catalogue_id, disabled', 'safe', 'on'=>'search'),
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

			'region' => array(self::BELONGS_TO, 'Regions', 'region_id'),
			'business' => array(self::BELONGS_TO, 'Business', 'business_id'),
			'country' => array(self::BELONGS_TO, 'Countries', 'country_id'),
			'city' => array(self::BELONGS_TO, 'Cities', 'city_id'),
			'neighborhood' => array(self::BELONGS_TO, 'Neighborhoods', 'neighborhood_id'),
			'region' => array(self::BELONGS_TO, 'Regions', 'region_id'),
			'catalogue' => array(self::BELONGS_TO, 'Catalogue', 'catalogue_id'),
			'favouriteCount' => array(self::STAT, 'Favourite', 'location_id'),
			'favourite'=>array(self::HAS_MANY,'Favourite','location'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'business_id' => 'Business',
			'country_id' => Yii::t("location","Country"),
            'region_id' =>  Yii::t("location",'Region'),
            'city_id' =>  Yii::t("location",'City'),
            'neighborhood_id' =>  Yii::t("location",'Neighborhood'),
            'name' =>  Yii::t("location",'Location Name'),
			'address' => Yii::t("location", 'Address'),
			'telephone' => Yii::t("location", 'Telephone'),
			'geo_longitude' => 'Geo Longitude',
			'geo_latitude' => 'Geo Latitude',
			'catalogue_id' => 'Catalogue',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('business_id',$this->business_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('neighborhood_id',$this->neighborhood_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('geo_longitude',$this->geo_longitude,true);
		$criteria->compare('geo_latitude',$this->geo_latitude,true);
		$criteria->compare('catalogue_id',$this->catalogue_id);
		$criteria->compare('disabled',$this->disabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*
     * get location details
     * @bussiness_id = business id (int)
	 * @catalogue_id = catalogue id (int)
     * @return: array
	 * Author: Jayesh Patel <jayesh.aghadiinfotech@gmail.com>
     */
	public function getLocationDetails($bussiness_id,$catalogue_id){
		
		$subquery = "";
		if(Yii::app()->user->id){
			$subquery = ',(';
			$subquery .= Yii::app()->db->createCommand()
					->select('favourite')
					->from(Favourite::model()->tableName())
					->where('business_id ='.$bussiness_id)
					->andWhere('catalogue_id='.$catalogue_id)
					->andWhere('userid='.Yii::app()->user->id)
					->andWhere('status=1')
					->andWhere('favourite>0')
					->getText();
			$subquery .= ') as favourite';
		}
		
		$result = Yii::app()->db->createCommand()
				->select('b.business_name,b.website,b.info1,b.info2,bc.category as business_category,l.* '.$subquery)
				->from(Business::model()->tableName().' as b')
				->leftJoin(BusinessCategory::model()->tableName().' as bc', 'bc.id=b.business_cat_id')
				->leftJoin(Locations::model()->tableName().' as l','l.business_id=b.id')
				->where('l.business_id ='.$bussiness_id)
				->andWhere('l.catalogue_id='.$catalogue_id)
				->queryRow();
		
		return $result;
		
	}

}