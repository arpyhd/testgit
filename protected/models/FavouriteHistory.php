<?php

/**
 * This is the model class for table "favourite".
 *
 * The followings are the available columns in table 'favourite':
 * @property integer $id
 * @property string $favourite
 * @property string $artist
 * @property integer $status
 */
class FavouriteHistory extends CActiveRecord
{
    public $activityName="";
    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Favourite the static model class
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
		return 'favourite_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('fid,activity_status,activityon,userid,keyword', 'safe', 'on'=>'search'),
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
                    "Favourite"=>array(self::BELONGS_TO,'Favourite','fid'),
                    
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'favourite' => 'Favourite',
			'artist' => 'Artist',
			'status' => 'Status',
		);
	}

	public function afterFind()
	{
	switch($this->activity_status)
	{
		case 0:
			$this->activityName=Yii::t('profile','Unfavourite');
		break;
		case 1:
			$this->activityName=Yii::t('profile','Business');
		break;
		case 2:
			$this->activityName=Yii::t('profile','Favourte');
		break;
		case 3:
			$this->activityName=Yii::t('profile','Location');
		break;
	}
	
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($days)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
                switch ($days)
                {
                    case 1:
                        $criteria->condition="userid=".Yii::app()->user->id." and datediff(now(),activityon)<$days";
                        break;
                    case 2:
                        $criteria->condition="userid=".Yii::app()->user->id." and datediff(now(),activityon)<$days and datediff(now(),activityon)>1";
                        break;
                    case 8:
                        $criteria->condition="userid=".Yii::app()->user->id." and datediff(now(),activityon)<$days";
                        break;
                    case 31:
                        $criteria->condition="userid=".Yii::app()->user->id." and datediff(now(),activityon)<$days";
                        break;
                    case 31:
                        $criteria->condition="userid=".Yii::app()->user->id." and datediff(now(),activityon)<$days";
                        break;
                }
                
                
				$criteria->group="fid,dayofyear(activityon)";
                $criteria->order="unix_timestamp(activityon) desc";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	
public function searchHomePage($days)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
                        $criteria->condition="userid=".Yii::app()->user->id;
          $criteria->order="id desc";
                   
                   $criteria->limit=10;       
                
		
                $criteria->order="unix_timestamp(activityon) desc";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));
	}
}