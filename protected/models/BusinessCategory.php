<?php

/**
 * This is the model class for table "business_category".
 *
 * The followings are the available columns in table 'business_category':
 * @property string $id
 * @property string $category
 * @property string $created_on
 * @property string $updated_on
 * @property string $created_by
 * @property string $updated_by
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Business[] $businesses
 */
class BusinessCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BusinessCategory the static model class
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
		return 'business_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('disabled', 'numerical', 'integerOnly'=>true),
			array('category', 'length', 'max'=>100),
			array('created_on, updated_on, created_by, updated_by', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category, created_on, updated_on, created_by, updated_by, disabled', 'safe', 'on'=>'search'),
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
			'businesses' => array(self::HAS_MANY, 'Business', 'business_cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category' => 'Category',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('updated_by',$this->updated_by,true);

		if($this -> disabled == 'null') {
			$criteria -> addCondition('disabled is null');
		} else {
			$criteria -> compare('disabled', $this -> disabled, true);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Use Yii's beforeSave function to add the modifier
	 */
	protected function beforeSave() {
		if($this ->isNewRecord) {
			$this->created_by = Yii::app()->user->id;
			$this->created_on =time();
		}
			
		$this->updated_by = Yii::app()->user->id;
		$this->updated_on =time();	
	
		return true;
	}
        
	/*To get all the catgory*/
	public static function getCategory() {            
		$categories = self::model()->findAll('disabled is null');	
		$catArray 	= CHtml::listData($categories,'id','category');	
		
		$t_listData		= array();
		$t_listData[''] = Yii::t('BusinessCategory','Categories');
		
		foreach($catArray as $key => $item) {   
			$t_listData[$key]=Yii::t('BusinessCategory',$item);
		} 
		
		return $t_listData;
	}
}