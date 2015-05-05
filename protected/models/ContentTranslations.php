<?php

/**
 * This is the model class for table "content_translations".
 *
 * The followings are the available columns in table 'content_translations':
 * @property integer $id
 * @property integer $content_id
 * @property string $language
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $add_datetime
 * @property string $latest_update
 * @property string $meta_keywords
 * @property string $meta_description
 *
 * The followings are the available model relations:
 * @property ContentPages $content
 */
class ContentTranslations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContentTranslations the static model class
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
		return 'content_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content_id, language', 'required'),
			array('content_id', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>10),
                        array('slug', 'unique'),
			array('title, slug', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
                        array('slug, description, meta_keywords, meta_description', 'safe'),
			array('id, content_id, language, title, slug, description, add_datetime, latest_update, meta_keywords, meta_description', 'safe', 'on'=>'search'),
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
			'content' => array(self::BELONGS_TO, 'ContentPages', 'content_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content_id' => 'Content',
			'language' => 'Language',
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
                 $this->slug= ContentPages::model()->createSlug($this->title);
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
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('language',$this->language,true);
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

    public function getTranslation($idContent, $language)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('content_id', $idContent);
        $criteria->compare('language', $language);
        return $this->find($criteria);
    }
}