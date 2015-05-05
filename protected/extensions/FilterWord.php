<?php

/**
 * WordFilter - This class is used to replace words that need to be filtered and return a clean text as per settings in the DB. 
 * @author Shobhit Shakya <meshobhitshakya@gmail.com>
 */
class FilterWord extends CApplicationComponent {

    /**
     * 
     * Replaces the specified bad words from table word_filter and returns a clean text
     *
     * @param string $value  the text to be filtered
     * @return string
     */
    public function replacement($value) {
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

}

?>