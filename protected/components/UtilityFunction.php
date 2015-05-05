<?php

/**
 * @author _uJJwAL_
 * 
 */
class UtilityFunction {
	public function sendMailOnSignup($to, $passHass, $type_id='1') {
		if($type_id == '1')
                {
		$subject = 'User Registration to Business.com';	
		$message = '
			<div>
                            <h4>Dear '. $to .',</h4><br/><br/>
                                    click this link to activate your account<a href="http://nirbuy.co.uk/site/verification?actid='.$passHass.'"> Activate User account</a> <br/>
                            Best regards,<br/>
                            business.com
			</div>';
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: noreply@nirbuy.com' . "\r\n" .
			'Reply-To: noreply@nirbuy.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
                }
                else {
                $subject = 'Bisiness Registration to Business.com';	
		$message = '
			<div>
                            <h4>Dear '. $to .',</h4><br/><br/>
                                    click this link to activate your account<a href="http://nirbuy.co.uk/site/verification?actid='.$passHass.'"> Activate Business account</a> <br/>
                            Best regards,<br/>
                            business.com
			</div>';
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: noreply@nirbuy.com' . "\r\n" .
			'Reply-To: noreply@nirbuy.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
                }
		mail($to, $subject, $message, $headers);
	}

	public function sendMailOnForgetPassword($to, $idHash) {
		$subject = 'Forget Password Business.com';	
		 $message = '
			<div>
				<h4>Dear '. $to .',</h4><br/><br/>
					Please follow the below mentioned url to change your password.
					<a href="http://nirbuy.co.uk/site/newpassword?changepassword='.$idHash.'">Click here to change your password.</a> <br/>
				Best regards,<br/>
				business.com
			</div>';
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: noreply@nirbuy.com' . "\r\n" .
			'Reply-To: noreply@nirbuy.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers) ;
	}

	 /**
     * 
     * Replaces the specified bad words from table word_filter and returns a clean text
     *
     * @param string $value  the text to be filtered
     * @return string
     */
    public function wordFilter($value) {
        $wordFilters = WordFilter::model()->findAll("status = 'y'");
        if($wordFilters){
            foreach($wordFilters as $wordFilter){
                //echo $wordFilter->word_filter."->".$wordFilter->replace_word;
                if(strpos($value, $wordFilter->word_filter)!== false){
                    $wordFilterUser = new WordFilterUser();
                    $wordFilterUser->word_filter_id = $wordFilter->id;
                    $wordFilterUser->user_id = Yii::app()->user->id;
                    $wordFilterUser->filtered_word = $wordFilter->word_filter;
                    $wordFilterUser->date_time = date("Y-m-d H:i:s");
                    $wordFilterUser->save();
                    $value = str_replace($wordFilter->word_filter, $wordFilter->replace_word, $value);
                }
            }
        }
        return $value;
    }

    public function wordTest($value) {
    	return $value;
    }
}