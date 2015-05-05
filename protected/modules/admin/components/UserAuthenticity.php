<?php
class UserAuthenticity extends CBaseUserIdentity
{
    private $_id; 
	private $_firstname; 	
    public $email;    
    public $password;
	

    /**
     * Constructor.
     * @param string $username username
     * @param string $password password
     */
    public function __construct($email, $password)
    {
            $this->email = $email;
            $this->password = $password;
    }

    public function authenticate()
    {   		
        $record = User::model()->findByAttributes(array('email' => $this->email));		
        if($record === null)
		{	         
			$this->errorCode=self::ERROR_USERNAME_INVALID;
        }
		else if($record->password !== md5($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
		else if($record->user_type == 'User')
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
        {
            $this->_id=$record->id;
			$this->_firstname=$record->first_name.' '.$record->last_name;			
			$this->setState('name', $record->first_name.' '.$record->last_name);
            $this->setState('user_type', $record->user_type);
			$this->setState('email', $record->email);
			//$this->setState('state', $record->state);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
	public function getName()
    {
        return $this->_firstname;
    }
}
?>