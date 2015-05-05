<?php

/**
 * This is the model class for table "product_detail".
 *
 * The followings are the available columns in table 'product_detail':
 * @property integer $product_id
 * @property integer $catalogue_id
 * @property integer $category_id
 * @property string $price
 * @property string $description
 * @property string $link
 * @property string $tags
 * @property integer $modified_by
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property Users $modifiedBy
 * @property Products $product
 * @property Catalogue $catalogue
 * @property Categories $category
 */
class ProductDetail extends CActiveRecord
{
    
        var $catalogue_name;
        var $category_name;
        var $product_name;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductDetail the static model class
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
		return 'product_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, catalogue_id, category_id', 'required'),
			array('product_id, catalogue_id, category_id, modified_by', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>20),
			array('link, tags', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, catalogue_id, category_id, price, link, tags, modified_by, modified_date', 'safe', 'on'=>'search'),
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
            'modifiedBy' => array(self::BELONGS_TO, 'Users', 'modified_by'),
			'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
			'catalogue' => array(self::BELONGS_TO, 'Catalogue', 'catalogue_id'),
			'category' => array(self::BELONGS_TO, 'Categories', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'catalogue_id' => 'Catalogue',
			'category_id' => 'Category',
			'price' => 'Price',
			'description' => 'Description',
			'link' => 'Link',
			'tags' => 'Tags',
			'modified_by' => 'Modified By',
			'modified_date' => 'Modified Date',
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
                
                $criteria->with = array("category","catalogue","product");

		$criteria->compare('product_id',$this->product_id);
                
                $criteria->compare('product_name',$this->product_name);
                
		$criteria->compare('t.catalogue_id',$this->catalogue_id);
		$criteria->compare('category_id',$this->category_id);
                
                $criteria->compare('catalogue.name',$this->catalogue_name);
		$criteria->compare('category.name',$this->category_name);
                
		$criteria->compare('price',$this->price,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('modified_date',$this->modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array(
                            'attributes' => array(
                                'catalogue_name' => array(
                                    'asc' => 'catalogue.name',
                                    'desc' => 'catalogue.name DESC',
                                ),
                                'product_name' => array(
                                    'asc' => 'product.name',
                                    'desc' => 'product.name DESC',
                                ),
                                'category_name' => array(
                                    'asc' => 'category.name',
                                    'desc' => 'category.name DESC',
                                ),
                                '*',
                            ),
                        ),
		));
	}

	/**
	 *
	 */
	protected function beforeSave() {
		$this -> modified_by = Yii::app()->user->id;
		$this -> modified_date = date('Y-m-d H:i:s');

		return true;
	}

	/**
	 *
	 */
	public function findProductDetailByCatalogue($productID, $catalogue) {
		$criteria = new CDbCriteria;
		$criteria -> condition = 'product_id = :productID';
		$criteria -> params = array(':productID' => $productID);
		$criteria -> addInCondition('catalogue_id', $catalogue);
		$criteria -> group = 'catalogue_id';

		return $this -> find($criteria);
	}

	/**
	 *
	 */
	public function deleteCatalogueByProduct($id) {
		$productCatalogue = $this -> findAllByAttributes(array('product_id' => $id));
		
		foreach($productCatalogue as $catalogue):
			$catalogue -> delete();
		endforeach;
	}
        
        /*
         * Deletes all product details in these catalogues
         * @catalogues: array of Catalogue items
         * @avoidSecondCategory: boolean, determines if it will skip products from the mysterious second category
         */

    public function deleteAllFromCatalogues($catalogues, $avoidSecondCategory) {
        $criteria = new CDbCriteria;
        

          $deleteCondition = "(";
       // $retrieveCondition = "(";
        $first = true;
        foreach ($catalogues as $c) {
            if (!$first) {
                $deleteCondition .= " OR ";
                //   $retrieveCondition .= " OR ";
            }
            else
                $first = false;
            $deleteCondition .= " product_detail.catalogue_id = $c->id ";
            // $retrieveCondition .= " t.catalogue_id = $c->id ";
        }
        $deleteCondition .= ")";
        //   $retrieveCondition .= ")";
        $criteria->addCondition($deleteCondition);
        if ($avoidSecondCategory) {
            /*  $scCriteria = $criteria;
              $scCriteria->select = "t.id"; */
            $secondCategoryResult = Yii::app()->db->createCommand()
                    ->select('product_detail.id')
                    ->from('product_detail')
                    ->join('categories c', 'c.id = product_detail.category_id')
                    ->where($deleteCondition . " AND c.type = 2")
                    ->queryAll();
            $secondCategoryIds = array();
            foreach ($secondCategoryResult as $scr)
                $secondCategoryIds[] = $scr['id'];
            $criteria->addNotInCondition('product_detail.id', $secondCategoryIds);
        }
        ProductDetail::model()->deleteAll($criteria);
    }

    /*
     * Deletes all product details on the Category identified by $categoryId created by user $userId
     * @param categoryId category parameter
     * @param catalogues array of Catalogue objects
     * @param userId user parameter
     */

    public function deleteAllOnCategoryAndCataloguesFromUser($categoryId, $catalogues, $userId) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('category_id = :categoryId');
        $criteria->addCondition('modified_by = :userId');
        $criteria->params = array(':categoryId' => $categoryId, ':userId' => $userId);
        foreach ($catalogues as $i => $c) {
            $criteria->addCondition('catalogue_id = :catalogue' . $i);
            $criteria->params[':catalogue' . $i] = $c->id;
        }

        //delete all previous:
        ProductDetail::model()->deleteAll($criteria);
    }
    
    /*
     * Gets the price in $/€ digit format from the text and returns it
     * Used in CSV parsing
     * @param 
     */
    public static function getPriceOfString($text) {
        //this won't be pretty...
        $stringArray = str_split($text);
        $price = "";
        $charsSinceComma = null;
        $charsSincePoint = null;
        $points = 0;
        $commas = 0;
      //  $points = 0;
        $usingCommaForThousandsSeparator = false;
        $usingPointForThousandsSeparator = false;
        foreach ($stringArray as $i => $c) {
            if (ctype_digit($c)) {
                //is digit, add
                if ($charsSinceComma == 3) {//limit reached
                    if (!$usingPointForThousandsSeparator)
                        $usingCommaForThousandsSeparator = true;
                    break;
                }
                elseif ($charsSincePoint == 3) {//limit reached
                    if (!$usingCommaForThousandsSeparator)
                        $usingPointForThousandsSeparator = true;
                    break;
                }
                $price .= $c;
                if ($charsSinceComma !== null) {
                    $charsSinceComma++;
                    if ($charsSinceComma == 2 && $usingPointForThousandsSeparator)
                        break;
                    elseif($charsSinceComma == 3)
                    {
                        if(!$usingPointForThousandsSeparator)
                            $usingCommaForThousandsSeparator = true;
                    }
                }

                elseif ($charsSincePoint !== null) {
                    $charsSincePoint++;
                    if ($charsSincePoint == 2 && $usingCommaForThousandsSeparator)
                        break;
                    elseif ($charsSincePoint == 3) {
                        if (!$usingCommaForThousandsSeparator)
                            $usingPointForThousandsSeparator = true;
                    }
                }
            } else { //no digit
                if ($c == ',' && strlen($price) > 0) {
                    //comma

                 /*   if ( $charsSinceComma !== null)//there was already a comma, end it
                        break;*/
                    if($usingPointForThousandsSeparator && $charsSinceComma > 0)
                        break;
                    if ($charsSinceComma == 3 && !$usingPointForThousandsSeparator)
                        $usingCommaForThousandsSeparator = true;
                    elseif ($charsSincePoint == 3 && !$usingCommaForThousandsSeparator)
                        $usingPointForThousandsSeparator = true;
                    if($usingPointForThousandsSeparator && $commas == 1)
                        break;
                    $commas++;
                    $charsSinceComma = 0;
                    $charsSincePoint = null;
                    $price .= $c;
                } elseif ($c == '.' && strlen($price) > 0) {
                    if($usingCommaForThousandsSeparator && $charsSincePoint > 0 )
                        break;
                    if ($charsSinceComma == 3 && !$usingPointForThousandsSeparator)
                        $usingCommaForThousandsSeparator = true;
                    elseif ($charsSincePoint == 3 && !$usingCommaForThousandsSeparator)
                        $usingPointForThousandsSeparator = true;
                    if($usingCommaForThousandsSeparator && $points == 1)
                        break;
                     $points++;
                    $charsSincePoint = 0;
                    $charsSinceComma = null;
                    $price .= $c;
                } else { //any other character
                    if (strlen($price) > 0)
                        break;
                }
            }
        }
        if ($price == "")
            return null;
        else {
           /* if (!$usingCommaForThousandsSeparator && !$usingPointForThousandsSeparator) {
                //NOTE: if you ever want to store prices as a float or a standard format, just uncomment the str_replace's
                //must detect
                if ($charsSinceComma > 0)
                    $usingPointForThousandsSeparator = true;
                elseif ($charsSincePoint > 0)
                    $usingCommaForThousandsSeparator = true;
            }
            if ($usingCommaForThousandsSeparator) {
                $price = str_replace(',', '', $price);
                $price = str_replace('.', ',', $price);
                if ($charsSincePoint == 1)
                    $price .= "0";
            }
            if ($usingPointForThousandsSeparator) {
                $price = str_replace('.', '', $price);
                $price = str_replace(',', ',', $price);
                if ($charsSinceComma == 1)
                    $price .= "0";
            }
            $price = str_replace(',00', '', $price);*/
            return $price;
        }
    }

}