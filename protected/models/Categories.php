<?php

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'categories':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property integer $added_by
 * @property string $added_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Catalogue[] $catalogues
 * @property ProductDetail[] $productDetails
 */ 
class Categories extends CActiveRecord
{
	public $business;
	public $catalogues;
	public $catalogueID;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Categories the static model class
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
		return 'categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('name, type', 'required'),
            array('type, added_by, modified_by, disabled', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>127),
            array('description, modified_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, type, description, added_by, added_date, modified_by, modified_date, disabled', 'safe', 'on'=>'search'),
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
            'catalogues' => array(self::MANY_MANY, 'Catalogue', 'category_catalogue(category_id, catalogue_id)'),
            'productDetails' => array(self::HAS_MANY, 'ProductDetail', 'category_id'),
        ); 
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'added_by' => 'Added By',
            'added_date' => 'Added Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
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

		$criteria = new CDbCriteria;
		$catalogues = array();

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.type',$this->type);

		/*$catalogue = Catalogue::model()->findCatalogueByBusiness($this -> business);
		
		foreach($catalogue as $catalog):
			array_push($catalogues, $catalog -> id);
		endforeach;
	
		$criteria -> addCondition('t.disabled is null');

		$criteria -> addCondition('c.disabled is null');
		$criteria -> select = 't.*, group_concat(distinct(c.name) separator ", ") as catalogues, group_concat(distinct(c.id) separator ", ") as catalogueID';
		$criteria -> join   = 'inner join category_catalogue cc on cc.category_id = t.id inner join catalogue c on c.id = cc.catalogue_id';
		$criteria -> addInCondition('cc.catalogue_id', $catalogues);	

		$criteria -> group = 't.name';
		$criteria -> order = 't.id desc';*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Use Yii's beforeSave function to add the modifier
	 */
	protected function beforeSave() {
		if($this -> isNewRecord) {
			$this -> added_by = Yii::app()->user->id;
			$this -> added_date = date('Y-m-d H:i:s');
		} else {
			$this -> modified_by = Yii::app()->user->id;
			$this -> modified_date = date('Y-m-d H:i:s');
		}
		
		return true;
	}

	/**
	* @todo - verify the query
	 * Returns the category list per catalogue
	 */
	public function findCategoryListByCatalogue($business, $catalogue = null) {
		$criteria = new CDbCriteria;

		$criteria -> select = 't.*, cc.*';
		$criteria -> join = 'inner join category_catalogue cc on t.id = cc.category_id inner join catalogue c on c.id = cc.catalogue_id inner join business b on b.id = c.business_id';

		$criteria -> condition = 'b.id = :business and t.disabled is null';
		$criteria -> params = array(':business' => $business);	

		if(!is_null($catalogue) && !empty($catalogue)) {
			$criteria -> addInCondition('cc.catalogue_id', $catalogue);
		}
		
		$criteria -> group = 't.id';
		$criteria -> order = 't.id desc';

		return $this -> findAll($criteria);
	}
        
        /*
         * If the connection category-catalogue doesn't exist, create it for the current category
         * @catalogueId = id of the catalogue this category will be assigned to
         */
        public function assignToCatalogue($catalogueId)
        {
           if(CategoryCatalogue::model()->findByAttributes(array('category_id' => $this->id, 'catalogue_id' => $catalogueId)) == null)
           {
               $categoryCatalogue = new CategoryCatalogue;
               $categoryCatalogue->category_id = $this->id;
               $categoryCatalogue->catalogue_id = $catalogueId;
               $categoryCatalogue->save();
           }
        }
        
        /*
         * Removes the category-catalogue relation for all categories from these catalogues, and deletes orphan categories implied at the end
         * @catalogues: array of Catalogue objects
         */

    public function removeCategoriesOfCatalogues($catalogues) {
        //get all relations implied
        $criteria = new CDbCriteria;
        $criteria->join = "JOIN categories c ON c.id = t.category_id";
        $criteria->addCondition("c.type != 2");
        $retrieveCondition = "";
        foreach ($catalogues as $i => $c) {
            if ($i > 0)
                $retrieveCondition .= " OR ";
            $retrieveCondition .= "catalogue_id = $c->id";
        }
        $criteria->addCondition($retrieveCondition);
        $relations = CategoryCatalogue::model()->findAll($criteria);
        foreach ($relations as $r) {
            if ($r->category->getCardinality() == 1)
                $r->category->delete(); //directly delete the category
            else
                $r->delete();
        }
    }

    /*
     * Returns the number of catalogues this category is related to
     */

    public function getCardinality() {
        return intval(Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('category_catalogue')
                        ->where("category_id = $this->id")
                        ->queryScalar());
    }

}