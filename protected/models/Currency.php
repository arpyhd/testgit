<?php

/**
 * This is the model class for table "currency".
 *
 * The followings are the available columns in table 'currency':
 * @property string $country
 * @property string $code
 * @property string $currency_name
 * @property string $symbol
 * @property integer $id
 * @property integer $country_id
 */
class Currency extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return City the static model class
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
        return 'currency';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('country_id, currency_name, code', 'required'),
            array('country_id, order', 'numerical', 'integerOnly'=>true),
            array('country', 'length', 'max'=>60),
            array('code, symbol', 'length', 'max'=>100),
            array('currency_name', 'length', 'max'=>32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('country, code, currency_name, symbol, id, country_id, order', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'country' => 'Country',
            'code' => 'Code',
            'currency_name' => 'Currency Name',
            'symbol' => 'Symbol',
            'id' => 'ID',
            'country_id' => 'Country',
            'order' => 'Order'
            
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

        $criteria->compare('country',$this->country,true);
        $criteria->compare('code',$this->code,true);
        $criteria->compare('currency_name',$this->currency_name,true);
        $criteria->compare('symbol',$this->symbol,true);
        $criteria->compare('id',$this->id);
        $criteria->compare('country_id',$this->country_id);
        $criteria->compare('t.order',$this->order);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                        'defaultOrder'=>'`order` DESC',
                    ),
        ));
    }

    public static function getCurrencyListData()
    {
        $countries = self::model()->findAll(array('order' => 'country'));

        $countryArray = CHtml::listData($countries,'id','code');

        $t_listData = array();
        foreach($countryArray as $key => $item) {
            $t_listData[$key] = Yii::t('BusinessCurrency', $item);
        }

        $countryArray = CMap::mergeArray(array('' => Yii::t('BusinessCurrency','Select currency')),$t_listData);

        return $countryArray;

    }

    public static function getCurrencySymbolById($id)
    {
        $criteria               = new CDbCriteria;
        $criteria->select       = 'symbol';
        $criteria->condition    = 'id = :cId';
        $criteria->params       = array(':cId' => $id);

        $obj = self::model()->find($criteria);

        return  $obj ? $obj->symbol : '';
    }
}