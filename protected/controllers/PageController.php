<?php
/**
 * Created by PhpStorm.
 * User: delroy
 * Date: 12.02.15
 * Time: 16:18
 */

class PageController extends Controller {
    public $layout = '//layouts/page-layout';
    public $showSearchBar = TRUE;
    public function actionIndex($slug)
    {
        $page = ContentPages::model()->getBySlug($slug);
       
        if ($slug == "welcome-page-2") {
            $this->showSearchBar = FALSE;
        }
        
        if (!$page) {
            throw new CHttpException(404, "this page is not found");
        }

        $language = explode('_', Yii::app()->language);
        if (!in_array(Yii::app()->sourceLanguage, $language)) {
            $translation = ContentTranslations::model()->getTranslation($page->id, Yii::app()->language);
            $page = $translation ? $translation : $page;
        }

        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/themes/bootstrap/js/helpers.js');
        
        //Yii::app()->createUrl("business/create/",array("id"=>$data->primaryKey)) 
        //$profileUrl= Yii::app()->createUrl("business/create/",array("id"=>$_GET['id'])); 
        $profileUrl= Yii::app()->createUrl("business/create/"); 
        $this->render('index', array('model'=>$page,'profileUrl'=>$profileUrl,'showSearchBar'=>$showSearchBar));
    }
}
