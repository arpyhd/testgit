<?php

/**
 * This is the model class for table "countries".
 *
 * The followings are the available columns in table 'countries':
 * @property integer $id
 * @property string $code
 * @property string $url
 * @property string $name
 * @property double $latitude
 * @property double $longitude
 * @property integer $regions
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Cities[] $cities
 * @property Locations[] $locations
 * @property Regions[] $regions0
 */
class Countries extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Countries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function primaryKey()
	{
		return 'code';		
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'countries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name', 'required'),
			array('regions, order', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('code', 'length', 'max'=>2),
			array('url, name', 'length', 'max'=>50),
                        array('code', 'unique', 'message' => 'Country code already exist!'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, url, name, latitude, longitude, regions, order', 'safe', 'on'=>'search'),
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
			'cities' => array(self::HAS_MANY, 'Cities', 'country'),
			'locations' => array(self::HAS_MANY, 'Locations', 'country_id'),
			'regions0' => array(self::HAS_MANY, 'Regions', 'country'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'url' => 'Url',
			'name' => 'Name',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'regions' => 'Regions',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('regions',$this->regions);
		$criteria->compare('t.order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
               'defaultOrder' => 't.`order` DESC',
            )
		));
	}

	/**
     * Get Country List
     */
    public static function getCountryListData()
    {
        $countries      = self::model()->findAll(array('order' => 'name'));
        $countryArray   = CHtml::listData($countries,'id','name');

        $t_listData=array();
        foreach($countryArray as $key => $item) {
            $t_listData[$key]=Yii::t('BusinessCountry', $item);
        }

        $countryArray = CMap::mergeArray(array('' => Yii::t('BusinessCountry','Select country')),$t_listData);
        return $countryArray;
    }
}