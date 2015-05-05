<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $id
 * @property string $name
 * @property integer $added_by
 * @property string $added_date
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property ProductDetail[] $productDetails
 */ 
class Products extends CActiveRecord
{
	public $catalogue;
	public $catalogueID;
	public $category;
	public $price;
	public $link;
	public $tags;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Products the static model class
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
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
	 	return array(
            array('name', 'required'),
            array('added_by, disabled', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, added_by, added_date, disabled', 'safe', 'on'=>'search'),
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
            'productDetails' => array(self::HAS_MANY, 'ProductDetail', 'product_id'),
            'addedBy' => array(self::BELONGS_TO, 'Users', 'added_by'),
            'productsRejectedImports' => array(self::HAS_MANY, 'ProductsRejectedImport', 'product_id'),
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
            'added_by' => 'Added By',
            'added_date' => 'Added Date',
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

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('added_by',$this->added_by);
        $criteria->compare('added_date',$this->added_date,true);
        $criteria->compare('disabled',$this->disabled);

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
		}
		
		return true;
	}

	/**
	 * List the catalogue product per business
	 */
	public function findProductByBusiness($businessID, $catalog=null, $category=null) {
	
		$criteria = new CDbCriteria;
		$criteria -> select = 't.*, p.category_id as category, group_concat(distinct(c.name) separator ", ") as catalogue, group_concat(distinct(c.id) separator ", ") as catalogueID, p.price as price, p.link as link';
		$criteria -> join = ' inner join product_detail p on p.product_id = t.id';
		$criteria -> condition = 't.disabled is null and c.disabled is null';

		if(is_null($catalog) || !is_array($catalog) || empty($catalog) || $catalog[0] == 'null') {	
			$catalogue = Catalogue::model()->findAllByAttributes(array('business_id' => $businessID));
			
			$catalog = array();
			foreach($catalogue as $cat) {
				array_push($catalog, $cat -> id);
			}
		} 

		$criteria -> join  .= ' inner join catalogue c on c.id = p.catalogue_id';	
		$criteria -> addInCondition('p.catalogue_id', $catalog);

		if(!empty($category) && !is_null($category) && $category[0] != 'null') {
			$criteria -> join .= ' inner join categories cat on cat.id = p.category_id';
			$criteria -> addCondition('cat.disabled is null');
			$criteria -> addInCondition('cat.id', $category);
		}

		$criteria -> group = 'p.product_id, p.price, p.link';
		$criteria -> order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => 20
			)
		));
	}

	public function searchProduct($businessID, $catalog=null, $category=null, $product) {
		$criteria = new CDbCriteria;
		$criteria -> select = 't.*, p.category_id as category, group_concat(distinct(c.name) separator ", ") as catalogue, group_concat(distinct(c.id) separator ", ") as catalogueID, p.price as price, p.link as link';
		$criteria -> join = ' inner join product_detail p on p.product_id = t.id';
		$criteria -> condition = 't.disabled is null and c.disabled is null';

		if(is_null($catalog) || !is_array($catalog) || empty($catalog) || $catalog[0] == 'null') {	
			$catalogue = Catalogue::model()->findAllByAttributes(array('business_id' => $businessID));
			
			$catalog = array();
			foreach($catalogue as $cat) {
				array_push($catalog, $cat -> id);
			}
		} 

		$criteria -> join  .= ' inner join catalogue c on c.id = p.catalogue_id';	
		$criteria -> addInCondition('p.catalogue_id', $catalog);

		if(!empty($category) && !is_null($category) && $category[0] != 'null') {
			$criteria -> join .= ' inner join categories cat on cat.id = p.category_id';
			$criteria -> addCondition('cat.disabled is null');
			$criteria -> addInCondition('cat.id', $category);
		}

		if(!empty($product)) {
			$criteria -> addCondition('t.name = :product');
			$criteria -> params = array_merge($criteria -> params, array(':product' => $product));
		}

		$criteria -> group = 'p.product_id, p.price, p.link';
		$criteria -> order = 't.id DESC';

    	return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
	}
        
        /*
         * Deletes all products from a user that aren't in any catalogue
         * @param userId: id of business user who uploaded these products
         */
        
        public static function deleteAbsentProductsFromUser($userId) {
        Products::model()->getDbConnection()->createCommand('DELETE  p
                FROM products p
                LEFT JOIN product_detail pd ON p.id = pd.product_id
                WHERE pd.id IS NULL AND p.added_by = ' . intval($userId))->execute();
    }
}