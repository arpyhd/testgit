<?php

/**
 * This is the model class for table "products_rejected_import".
 *
 * The followings are the available columns in table 'products_rejected_import':
 * @property integer $id
 * @property integer $catalogue_id
 * @property integer $category_id
 * @property integer $product_id
 * @property string $link
 * @property string $price
 * @property integer $modified_by
 * @property string $modified_date
 * @property string $description
 * @property integer $csv_upload_id
 * @property integer $line
 *
 * The followings are the available model relations:
 * @property ProductRejectedCustomValues[] $productRejectedCustomValues
 * @property Products $product
 * @property CsvUploads $csvUpload
 */
class ProductRejectedImport extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductRejectedImport the static model class
     */
    public $specialFailure = null;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'products_rejected_import';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('csv_upload_id, line', 'required'),
            array('catalogue_id, category_id, product_id, modified_by, csv_upload_id, line', 'numerical', 'integerOnly' => true),
            array('link', 'length', 'max' => 255),
            array('price', 'length', 'max' => 20),
            array('modified_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, catalogue_id, category_id, product_id, link, price, modified_by, modified_date, description, csv_upload_id, line', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            //'productRejectedCustomValues' => array(self::HAS_MANY, 'ProductRejectedCustomValues', 'product_id'),
            'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
            'csvUpload' => array(self::BELONGS_TO, 'CsvUploads', 'csv_upload_id'),
            'catalogue' => array(self::BELONGS_TO, 'Catalogue', 'catalogue_id'),
            'category' => array(self::BELONGS_TO, 'Categories', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
   /* public function attributeLabels() {
      /*  return array(
            'id' => 'ID',
            'catalogue_id' => 'Catalogue',
            'category_id' => 'Category',
            'product_id' => 'Product',
            'link' => 'Link',
            'price' => 'Price',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
           'tags' => 'Tags',
            'csv_upload_id' => 'Csv Upload',
            'line' => 'Line',
        );
    }*/

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('catalogue_id', $this->catalogue_id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('modified_by', $this->modified_by);
        $criteria->compare('modified_date', $this->modified_date, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('csv_upload_id', $this->csv_upload_id);
        $criteria->compare('line', $this->line);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}