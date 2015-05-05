<?php
class MyController extends CController
{
    function init() {
        parent::init();
        $app = Yii::app();
		$lang =Yii::app()->language;
		
		if(isset($_POST['_lang'])) {
            $app -> language            = $_POST['_lang'];
            $app -> session['_lang']    = $app -> language;
        } else if (isset($app -> session['_lang'])) {
            $app -> language = $app -> session['_lang'];
        }
    }
}
?>