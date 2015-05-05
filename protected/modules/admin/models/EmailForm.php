<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class EmailForm extends CFormModel
{
	public $subject;
	public $content;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// subject and content are required
			array('subject, content', 'required'),
			array('subject, content', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
                    'subject'=>'Subject',
                    'content'=>'Content',
                    'receipients'=>'',
		);
	}
}
