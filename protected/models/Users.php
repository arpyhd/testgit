<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $pass_hash
 * @property string $id_hash
 * @property string $language
 * @property integer $security_question
 * @property string $security_answer
 * @property string $added_date
 * @property string $modified_date
 * @property string $user_type
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Business[] $businesses
 */
class Users extends CActiveRecord {

    public $confirm_email;
    public $current_password;
    public $new_password;
    public $confirm_password;
    public $from_date;
    public $to_date;
    public $business_name;
    public $bad_words_count;
    public $registration_type;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, password', 'required'),
            array('disabled', 'numerical', 'integerOnly' => true),
            array('first_name, last_name', 'length', 'max' => 30),
            array('email, pass_hash, id_hash', 'length', 'max' => 50),
            array('password', 'length', 'max' => 32),
            array('user_type', 'length', 'max' => 8),
            array('email', 'email'),
            array('email', 'unique'),
            array('confirm_email', 'required', 'on' => 'register'),
            array('confirm_email', 'compare', 'compareAttribute' => 'email', 'on' => 'register'),
            array('current_password, new_password, confirm_password', 'required', 'on' => 'changepassword'),
            array('confirm_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'changepassword'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, first_name, business_name, last_name, email, password, pass_hash, id_hash, added_date, modified_date, user_type, disabled', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'business' => array(self::HAS_ONE, 'Business', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'pass_hash' => 'Pass Hash',
            'id_hash' => 'Id Hash',
            'language' => 'Language',
            'registration_type' => 'Registration Type',
            'security_question' => 'Security Question',
            'security_answer' => 'Security Answer',
            'added_date' => 'Added Date',
            'modified_date' => 'Modified Date',
            'user_type' => 'User Type',
            'disabled' => 'Disabled',
            'confirm_email' => 'Confirm Email',
            'current_password' => Yii::t("profile",'Current Password'),
            'new_password' => Yii::t("profile",'New Password'),
            'confirm_password' => Yii::t("profile",'Confirm Password')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array("business");

        $criteria->select = array(
                "*",
                "(select business_name from business where user_id = t.id group by t.id) as business_name",
                "(select count(0) from word_filter_user where user_id = t.id) as bad_words_count",
            );
        
        if ((isset($this->from_date) && trim($this->from_date) != "") && (isset($this->to_date) && trim($this->to_date) != ""))
            $criteria->addBetweenCondition('added_date', '' . $this->from_date . '', '' . $this->to_date . '');

        $criteria->compare('id', $this->id);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('business_name', $this->business_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('pass_hash', $this->pass_hash, true);
        $criteria->compare('id_hash', $this->id_hash, true);
        $criteria->compare('added_date', $this->added_date, true);
        $criteria->compare('modified_date', $this->modified_date, true);
        $criteria->compare('user_type', $this->user_type, true);
        $criteria->compare('disabled', $this->disabled);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                            'attributes' => array(
                                'business_name' => array(
                                    'asc' => 'business_name',
                                    'desc' => 'business_name DESC',
                                ),
                                'bad_words_count' => array(
                                    'asc' => 'bad_words_count',
                                    'desc' => 'bad_words_count DESC',
                                ),
                                '*',
                            ),
                        ),
                ));
    }
    
    
    /*
     * returns count from records in table word_filter_user
     * @return integer count from records in table word_filter_user
     */
    
    public function getBad_words_count(){
        return 0;
    }

    /*
     * returns all the catalogues this user has (array of objects)
     */

    public function getUserCatalogues() {

       /* $catalogues = array();
        foreach ($this->businesses as $ub) {
            $catalogues = array_merge($catalogues, $ub->catalogues);
        }*/
        return $this->business->catalogues;
    }
    
    /*
     * Returns array of Catalogue objects for all catalogues that are currently active for this user
     */

    public function getUserActiveCatalogues() {
        $criteria = new CDbCriteria;
        $criteria->join = "JOIN business b ON t.business_id = b.id";
        $criteria->addCondition("b.user_id = $this->id");
        $criteria->addCondition("t.disabled = 0 OR t.disabled IS NULL");
        return Catalogue::model()->findAll($criteria);
    }

    /*
     * returns all the categories this user has (array of objects) with id as index
     * @firstTypeOnly: boolean, determines if it should retrieve only 1st type categories
     */

    public function getUserActiveCategories($firstTypeOnly) {

        $categories = array();
        foreach ($this->getUserActiveCatalogues() as $uc) {
            $categoryCatalogueRelations = CategoryCatalogue::model()->findAllByAttributes(array('catalogue_id' => $uc->id));
            foreach ($categoryCatalogueRelations as $ccr) {
                if ($ccr->category->disabled != '1' && (!$firstTypeOnly || $ccr->category->type == 1))
                    $categories[$ccr->category->id] = $ccr->category;
            }
            //  $categories = array_merge($categories,$ub->catalogues);
        }
        return $categories;
    }

    /**
     * Use Yii's beforeSave function to add the modifier
     */
    protected function beforeSave() {
        if($this -> isNewRecord) {
            $this -> added_date = date('Y-m-d H:i:s', time());
        }

        $this -> modified_date = date('Y-m-d H:i:s', time());
        
        return true;
    }

}