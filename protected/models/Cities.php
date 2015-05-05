<?php

/**
 * This is the model class for table "cities".
 *
 * The followings are the available columns in table 'cities':
 * @property string $id
 * @property string $country
 * @property string $region
 * @property string $url
 * @property string $name
 * @property double $latitude
 * @property double $longitude
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Countries $country0
 * @property Locations[] $locations
 * @property Neighborhoods[] $neighborhoods
 */
class Cities extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cities the static model class
	 */
    public $region_name;
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('region, country, name', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
            array('name', 'unique','message'=>'City already exists!'),
			array('country', 'length', 'max'=>2),
			array('region', 'length', 'max'=>20),
			array('url, name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country, region, url, name, latitude, longitude, order, region_name', 'safe', 'on'=>'search'),
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
			'country0' => array(self::BELONGS_TO, 'Countries', 'country'),
			'locations' => array(self::HAS_MANY, 'Locations', 'city_id'),
			'neighborhoods' => array(self::HAS_MANY, 'Neighborhoods', 'city_id'),
        		'regions' => array(self::BELONGS_TO, 'Regions', 'region'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country' => 'Country',
			'region' => 'Region',
			'url' => 'Url',
			'name' => 'Name',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'order' => 'Order',
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

		$criteria->compare('t.id',$this->id,true);
        $criteria->with = array('regions');
        $criteria->compare('regions.name',$this->region_name, true );
		$criteria->compare('country',$this->country,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('t.order',$this->order);
        $criteria->addCondition('t.region != 0');
                
		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
           	'sort'=>array(
                'defaultOrder' => 't.`order` DESC',
                'attributes'=>array(
                    'region_name'=>array(
                        'asc'=>'regions.name',
                        'desc'=>'regions.name DESC',
                    ),
                    '*',
                ),
            ),
        ));
	}

	/**
	* Find Cities By Country and region
	* Reviewed by _uJJwAL_
	*/
	public function findCitiesByCountryRegion($countryID, $regionID){
		$criteria = new CDbCriteria;

		$criteria -> condition = 'c.id = :country and r.id = :region';
		$criteria -> params = array(':country' => $countryID, ':region' => $regionID);

		$criteria -> with = array('country0' => array('alias' => 'c'), 'region0' => array('alias' => 'r'));

		return $this -> findAll($criteria);
	}
}