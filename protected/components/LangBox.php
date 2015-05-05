<?php
class LangBox extends CWidget
{
    public function run()
    {
        //$currentLang = Yii::app()->language;
         $currentLang = (string)Yii::app()->request->cookies['language'];
        
        if($currentLang == "") {
            $known_langs = array('en_GB','fr','de','es','it');

            $user_pref_lang_str = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    	
            $user_pref_langs = explode(',', $user_pref_lang_str);
            foreach($user_pref_langs as $idx => $lang) {               
                if (in_array($lang, $known_langs)) {
                    $currentLang=$lang;
                    break;
                }
            }
        }
        
        $this->render('langBox', array('currentLang' => $currentLang));
    }
}
?>