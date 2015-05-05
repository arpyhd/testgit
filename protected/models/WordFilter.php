<?php

/**
 * This is the model class for table "word_filter".
 *
 * The followings are the available columns in table 'word_filter':
 * @property integer $id
 * @property string $word_filter
 * @property string $replace_word
 * @property string $status
 */
class WordFilter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WordFilter the static model class
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
		return 'word_filter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('word_filter, status, replace_word', 'required'),
			array('word_filter', 'length', 'max'=>20),
			array('status', 'length', 'max'=>1),
                        array('replace_word', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, word_filter, status', 'safe', 'on'=>'search'),
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
			'word_filter' => 'Word Filter',
			'replace_word' => 'Replace Word',
			'status' => 'Status',
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
		$criteria->compare('word_filter',$this->word_filter,true);
		$criteria->compare('replace_word',$this->replace_word,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}