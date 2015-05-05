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
 * @property string $user_type
 * @property string $added_date
 * @property string $pass_hash
 * @property string $id_hash
 * @property string $status
 */
class UserLogin extends CActiveRecord
{
	private $_identity;
	public $rememberMe;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserLogin the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password', 'required'),
			array('first_name, last_name', 'length', 'max'=>30),
			array('email', 'length', 'max'=>50),
			array('password, pass_hash, id_hash', 'length', 'max'=>32),
			array('user_type', 'length', 'max'=>8),
			array('email', 'email'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, first_name, last_name, email, password, user_type, added_date, pass_hash, id_hash, status', 'safe', 'on'=>'search'),
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
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'password' => 'Password',
			'user_type' => 'User Type',
			'added_date' => 'Added Date',
			'pass_hash' => 'Pass Hash',
			'id_hash' => 'Id Hash',
			'rememberMe' => 'Keep me Logged In'
		);
	}

	/**
	 * 
	 */
	public function login($withoutHashPassword=False)
        {
            if($this->_identity === null) {		
                $this->_identity = new UserIdentity($this->email,$this->password);
                $this->_identity->authenticate($withoutHashPassword);
            }
            
            if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
            {
                $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
                Yii::app()->user->login($this->_identity,$duration);
                return true;
            }
            else
                return false;        
        }
        
        
        /**
	 * Login used for admin 
	 */
	public function alogin()
        {
            if($this->_identity === null) {		
                $this->_identity = new UserIdentity($this->email,$this->password);
                $this->_identity->authenticate();
            }

            if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
            {
                $duration = 3600 * 24 * 360; // 360 days
                Yii::app()->user->login($this->_identity, $duration);
                return true;
            }
            else
                return false;        
        }

    /**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
            if(!$this->hasErrors())
            {
                    $this->_identity=new UserIdentity($this->email,$this->password);
                    if(!$this->_identity->authenticate())
                        $this->addError('password','Incorrect email or password.');
            }
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password,$withoutHashPassword=FALSE)
	{
            if($withoutHashPassword)
            {
                return $password==$this->password;
            }
            else
            {
                return $this->hashPassword($password)==$this->password;
            }
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return md5($password);
	}
}