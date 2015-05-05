<?php

/**
 * This is the model class for table "neighborhoods".
 *
 * The followings are the available columns in table 'neighborhoods':
 * @property integer $id
 * @property string $city_id
 * @property string $name
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Locations[] $locations
 * @property Cities $city
 */
class Neighborhoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Neighborhoods the static model class
	 */
    public $city_name;
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'neighborhoods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, name', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('city_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>60),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, city_id, name, order, city_name', 'safe', 'on'=>'search'),
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
    			'locations' => array(self::HAS_MANY, 'Locations', 'neighborhood_id'),
			'city' => array(self::BELONGS_TO, 'Cities', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => 'City',
			'name' => 'Name',
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
        $criteria->with = array('city');
        $criteria->compare('city.name',$this->city_name,true);
        $criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
			   'defaultOrder' => 't.`order` DESC',
			    'attributes'=>array(
			        'city_name'=>array(
			            'asc'=>'city.name',
			            'desc'=>'city.name DESC',
			        ),
			        '*',
			    ),
			),
		));
	}

	/**
	 * Find Neigborhood By City
	 * @author _uJJwAL_
	 */
	public function findNeighborhoodByCity($cityID) {
		$criteria = new CDbCriteria;

		$criteria -> condition = 'city_id = :city';
		$criteria -> params = array(':city' => $cityID);

		return $this -> findAll($criteria);
	}
}