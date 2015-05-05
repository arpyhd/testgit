<?php

/**
 * This is the model class for table "word_filter_user".
 *
 * The followings are the available columns in table 'word_filter_user':
 * @property integer $id
 * @property integer $word_filter_id
 * @property integer $user_id
 * @property string $filtered_word
 * @property string $date_time
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property WordFilter $wordFilter
 */
class WordFilterUser extends CActiveRecord
{
    
    var $user_name;
    var $business;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WordFilterUser the static model class
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
		return 'word_filter_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('word_filter_id, user_id, filtered_word, date_time', 'required'),
			array('word_filter_id, user_id', 'numerical', 'integerOnly'=>true),
			array('filtered_word', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, word_filter_id, user_id, user_name, filtered_word, date_time', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'wordFilter' => array(self::BELONGS_TO, 'WordFilter', 'word_filter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'word_filter_id' => 'Word Filter',
			'user_id' => 'User',
			'filtered_word' => 'Filtered Word',
			'date_time' => 'Date Time',
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
                $criteria->with = array("user", "user.business");
                
                $criteria->select = array(
                    "*",
                    "user.first_name as first_name",
                    "business.business_name as business",
                );
                
                if(!empty($this->user_name) || !empty($this->business)){
                    $criteria ->addCondition("business.first_name LIKE CONCAT('%', :filterProperties , '%') OR business.last_name LIKE CONCAT('%', :filterProperties , '%')");
                    $criteria ->addCondition("business.business_name LIKE CONCAT('%', :busname , '%')");
                    $criteria->params = array(':filterProperties' => $this->user_name, ":busname"=>$this->business); 
                }
                   
		$criteria->compare('id',$this->id);
		//$criteria->compare('business',$this->user->business->business_name);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('filtered_word',$this->filtered_word,true);
		$criteria->compare('date_time',$this->date_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array(
                            'attributes' => array(
                                'user_name' => array(
                                    'asc' => 'first_name',
                                    'desc' => 'first_name DESC',
                                ),
                                'business' => array(
                                    'asc' => 'business',
                                    'desc' => 'business DESC',
                                ),
                                '*',
                            ),
                        ),
		));
	}
}