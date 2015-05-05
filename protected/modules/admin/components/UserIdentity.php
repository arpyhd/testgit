<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        $criteria = new CDbCriteria();
        $criteria->condition = 'email = :email';
        $criteria->params = array(':email' => $this->username);

        $criteria->addCondition('disabled is null');

        $user = UserLogin::model()->find($criteria);

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else if ($user->user_type == 'admin' && Yii::app()->urlManager->parseUrl(Yii::app()->request) != 'admin/user/login') {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id;

            Yii::app()->user->setState('name', $user->first_name . ' ' . $user->last_name);
            Yii::app()->user->setState('user_type', $user->user_type);
            Yii::app()->user->setState('email', $user->email);

            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}