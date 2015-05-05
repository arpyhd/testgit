<?php
class BeginRequest extends CBehavior {
    // The attachEventHandler() mathod attaches an event handler to an event. 
    // So: onBeginRequest, the handleBeginRequest() method will be called.
    public function attach($owner) {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }
 
    public function handleBeginRequest($event) {        
        $app = Yii::app();
        $user = $app->user;
 
        if (isset($_POST['_lang'])) {
            $app -> language = $_POST['_lang'];
            $app -> user -> setState('_lang', $_POST['_lang']);
           
            $cookie = new CHttpCookie('language', $_POST['_lang']);
            $cookie -> expire = time() + (60*60*24*365); // (1 year)
            
            Yii::app() -> request -> cookies['language'] = $cookie;
        } else if ($app -> user -> hasState('_lang')) {
            $app -> language = $app -> user -> getState('_lang');
        }
        else if(isset(Yii::app()->request->cookies['language'])) {
            $app -> language = Yii::app() -> request -> cookies['language'] -> value;
        } else {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            switch ($lang){
                case "fr":
                case "it":
                case "es":
                case "de":
                    $app -> language = $lang;

                    $cookie = new CHttpCookie('language', $lang);
                    $cookie -> expire = time() + (60*60*24*365); // (1 year)
                    Yii::app() -> request -> cookies['language'] = $cookie;
                break;
            }
        }	

        /* save to db */
        if(!Yii::app()->user->isGuest) {
            $user = Users::model()->findByPk(Yii::app()->user->id);
            if($user) {
            	$user -> language = $app -> language;
            	$user -> save();
        	}
        }	
    }
}