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
class Favourite extends CActiveRecord
{
    public $activity_status=0,$location_status=0,$business_status=0;
    public $business_name="",$location_name="";
	public $keyword="";
    public $noAftersave = false;
    public $nr;
    public $bus;
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
		return 'favourite';
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
			array('userid,cityid,business_id,favourite,keyword,location, business,favourite_on,location_on,business_on, business_name,catalogue_id,name,location_status,business_status, updatedon, status', 'safe', 'on'=>'search'),
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
          'Business'=> array(self::BELONGS_TO, 'Business', 'business_id'),
			'Location' => array(self::BELONGS_TO, 'Locations', '', 'foreignKey' => array('business_id'=>'business_id','catalogue_id'=>'catalogue_id')),
            'Location1'=>array(self::BELONGS_TO, 'Locations','location'),
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
        $this->activity_status=$this->favourite;
		$this->location_status=$this->location;
		$this->business_status=$this->business;
		
    }

    public function afterSave()
	{
		/* Status=0 Unfavourite 
		Status=1 click on business
		status=2 click on favourte
		status=3 click on location
		*/
        if(!$this->noAftersave) {
            $status=0;
            if($this->activity_status!=$this->favourite)
                $status=2;
            if($this->business_status!=$this->business)
                $status=1;
            if($this->location_status!=$this->location)
                $status=3;

            $model=new FavouriteHistory();

            $model->userid=$this->userid;
            $model->fid=$this->id;

            $model->activity_status=$status;
            if($this->status==0)
                $model->activity_status=0;

            $model->keyword=$this->keyword;
            $model->save(false);
        }

    	return true;
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
        $criteria->with = array( 'Business','Location' );
               
        $this->status=1;
                   
		$criteria->compare('id',$this->id);
		$criteria->compare('status',$this->status);
        $criteria->compare('userid',Yii::app()->user->id);
        $criteria->compare('Business.business_name',$this->business_name,true);
        $criteria->compare('Location.name',$this->location_name,true);
        $criteria->compare('favourite',1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
		
	public function searchHomePage($days)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->condition=" (location>0 or business>0 or favourite >0 or catalogue_id > 0) and userid=".Yii::app()->user->id;
		$criteria->order="id desc";
                   
        $criteria->limit=10;                      
		
        $criteria->order="unix_timestamp(updatedon) desc";
			return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));
	}

	public function countFav()
	{
		if($this -> catalogue_id > 0) {
			$sql="select count(id) from favourite where (favourite >0) and business_id=".$this->business_id . " and catalogue_id=".$this->catalogue_id;
			return Yii::app()->db->createCommand($sql)->queryScalar(); 
		} else {
			return 0;
		}
	}

}