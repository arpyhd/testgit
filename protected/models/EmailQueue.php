<?php

/**
 * This is the model class for table "email_queue".
 *
 * The followings are the available columns in table 'email_queue':
 * @property integer $id
 * @property string $email_title
 * @property string $email_content
 * @property string $email_from
 * @property string $email_to
 * @property string $email_queued_date
 * @property string $email_sent_date
 * @property integer $email_status
 */
class EmailQueue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EmailQueue the static model class
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
		return 'email_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email_title, email_content, email_from, email_to, email_queued_date', 'required'),
			array('email_status', 'numerical', 'integerOnly'=>true),
			array('email_title', 'length', 'max'=>255),
			array('email_from', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email_title, email_content, email_from, email_to, email_queued_date, email_sent_date, email_status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email_title' => 'Email Title',
			'email_content' => 'Email Content',
			'email_from' => 'Email From',
			'email_to' => 'Email To',
			'email_queued_date' => 'Email Queued Date',
			'email_sent_date' => 'Email Sent Date',
			'email_status' => 'Email Status',
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
		$criteria->compare('email_title',$this->email_title,true);
		$criteria->compare('email_content',$this->email_content,true);
		$criteria->compare('email_from',$this->email_from,true);
		$criteria->compare('email_to',$this->email_to,true);
		$criteria->compare('email_queued_date',$this->email_queued_date,true);
		$criteria->compare('email_sent_date',$this->email_sent_date,true);
		$criteria->compare('email_status',$this->email_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}