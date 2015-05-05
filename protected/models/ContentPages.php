<?php

/**
 * This is the model class for table "content_pages".
 *
 * The followings are the available columns in table 'content_pages':
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $add_datetime
 * @property string $latest_update
 * @property string $meta_keywords
 * @property string $meta_description
 */
class ContentPages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContentPages the static model class
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
		return 'content_pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, slug, description, add_datetime, latest_update, meta_keywords, meta_description', 'required'),
			array('title, slug', 'length', 'max'=>255),
                        array('slug', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, slug, description, add_datetime, latest_update, meta_keywords, meta_description', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'slug' => 'Slug',
			'description' => 'Description',
			'add_datetime' => 'Add Datetime',
			'latest_update' => 'Latest Update',
			'meta_keywords' => 'Meta Keywords',
			'meta_description' => 'Meta Description',
		);
	}
        
        
        /**
	 * To generate add date or update data and also to generate slug before update or insert actions
	 * @return Boolen
	 */
        public function beforeValidate(){
            if(parent::beforeValidate())
            {
                 $this->slug= $this->createSlug($this->title);
                 if($this->isNewRecord){
                     $this->add_datetime = date("Y-m-d H:i:s");
                 }
                 $this->latest_update = date("Y-m-d H:i:s");
                 return true;
            }
            return false;
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('add_datetime',$this->add_datetime,true);
		$criteria->compare('latest_update',$this->latest_update,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_description',$this->meta_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
	 * created slug string out of title
	 * @return string
	 */
        public function createSlug($string) {
            //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
            $string = strtolower($string);
            //Strip any unwanted characters
            $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
            //Clean multiple dashes or whitespaces
            $string = preg_replace("/[\s-]+/", " ", $string);
            //Convert whitespaces and underscore to dash
            $string = preg_replace("/[\s_]/", "-", $string);
            return $string;
        }

    public function getBySlug($slug)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('slug', $slug);
        return $this->find($criteria);
    }
}