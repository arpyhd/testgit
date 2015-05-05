<?php

/**
 * This is the model class for table "regions".
 *
 * The followings are the available columns in table 'regions':
 * @property integer $id
 * @property string $country
 * @property string $region
 * @property string $url
 * @property string $name
 * @property double $latitude
 * @property double $longitude
 * @property string $cities
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Locations[] $locations
 * @property Countries $country0
 */
class Regions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Regions the static model class
	 */
    public $country_name;
    
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'regions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country, name', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('country', 'length', 'max'=>2),
			array('region', 'length', 'max'=>3),
			array('url, name', 'length', 'max'=>50),
			array('cities', 'length', 'max'=>4),
                        array('name', 'unique', 'message' => 'Region name already exist!'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country, region, url, name, latitude, longitude, cities, order, country_name', 'safe', 'on'=>'search'),
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
			'locations' => array(self::HAS_MANY, 'Locations', 'region_id'),
			'country0' => array(self::BELONGS_TO, 'Countries', 'country'),
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
			'cities' => 'Cities',
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

		$criteria->compare('t.id',$this->id);
        $criteria->with = array('country0');
        $criteria->compare('country0.name',$this->country_name, true );
		$criteria->compare('country',$this->country,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('cities',$this->cities,true);
		$criteria->compare('t.order',$this->order);

		return new CActiveDataProvider($this, array(
	        'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder' => 't.`order` DESC',
                'attributes'=>array(
                    'country_name'=>array(
                        'asc'=>'country0.name',
                        'desc'=>'country0.name DESC',

                    ),
                    '*',
                ),
	        ),
	    ));
	}

    /**
     * Find Region By Country
     * @author _uJJwAL_
     */
    public function findRegionByCountry($id) {
       $criteria = new CDbCriteria;

       $criteria -> condition = 'c.id = :id';
       $criteria -> params = array(':id' => $id);
       $criteria -> with = array('country0' => array('alias' => 'c'));

       return $this -> findAll($criteria);
   }
}