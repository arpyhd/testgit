<?php

class CatalogueController extends Controller {

    public $catalogue;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/new-layout-business';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * @author _uJJwAL_
     */
    public function actionIndex() {
        $this->title = Yii::t('catalogue', "Catalogue");
        $this->subtitle = Yii::t('catalogue', 'Catalogue View');

        if (Yii::app()->request->isAjaxRequest && isset($_POST) && !empty($_POST)) {
            if (isset($_POST['catalogue'])) {
                $catalogue = $_POST['catalogue'];
                $catalogueArray = array_values(array_filter(explode(',', $catalogue)));
                Yii::app()->session['catalogues'] = $catalogueArray;
            } else {
                $catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
            }

            if (isset($_POST['category'])) {
                $category = $_POST['category'];
                $categoryArray = array_values(array_filter(explode(',', $category)));
                Yii::app()->session['categories'] = $categoryArray;
            } else {
                $categoryArray = isset(Yii::app()->session['categories']) ? Yii::app()->session['categories'] : "";
            }

            if (isset($_POST['checked'])) {
                $checked = $_POST['checked'];
            }

            $categoryList = Categories::model()->findCategoryListByCatalogue($this->business, $catalogueArray);
            $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
            $productList = Products::model()->findProductByBusiness($this->business, $catalogueArray, $categoryArray);
            $categoryString = $this->renderPartial('_category', array('categories' => $categoryList), true);
            $catalogueString = $this->renderPartial('_view', array('catalogues' => $catalogues), true);
            $productListString = $this->renderPartial('_catalogue', array('productList' => $productList, 'checked' => $checked, 'businessId' => $this->business), true);

            $return['categories'] = $categoryString;
            $return['catalogues'] = $catalogueString;
            $return['product'] = $productListString;

            echo json_encode($return);
            Yii::app()->end();
        }

        $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
        $catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
        $categoryArray = isset(Yii::app()->session['categories']) ? Yii::app()->session['categories'] : "";

        if (!is_array($categoryArray)) {
            $categoryArray = array_values(array_filter(explode(',', $categoryArray)));
        }

        if (!is_array($catalogueArray)) {
            $catalogueArray = array_values(array_filter(explode(',', $catalogueArray)));
        }

        $productList = Products::model()->findProductByBusiness($this->business, $catalogueArray, $categoryArray);
        $categoryList = Categories::model()->findCategoryListByCatalogue($this->business, $catalogueArray);
        $lastPublishTime = Catalogue::model()->findLastCataloguePublishTime($this->business);
        
        if (date('Y-m-d H:i:s', strtotime($lastPublishTime)) == $lastPublishTime)    
        {
            $lastPublishTime = date('F-d-Y h:i A',strtotime($lastPublishTime));
        }
        else
        {
            $lastPublishTime = NULL;
        }
        //echo $lastPublishTime;
        //exit;
        $this->render('index', array('productList' => $productList, 'catalogues' => $catalogues, 'categories' => $categoryList, 'businessId' => $this->business,'lastPublishTime'=>$lastPublishTime));
    }

    /**
     * Reviewed by _uJJwAL_
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Reviewed by _uJJwAL_
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Catalogue;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Catalogue'])) {
            $model->attributes = $_POST['Catalogue'];
            $model->business_id = $this->business;

            if ($model->validate()) {
                $exist = Catalogue::model()->findByAttributes(array('business_id' => $this->business, 'name' => $model->name));
                if (empty($exist)) {
                    $model->catalogue_id = Catalogue::model()->findNextCatalogueIdByBusiness($this->business);
                    $model->save();

                    Yii::app()->user->setFlash('success', 'Successfully Created Catalogue');

                    $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);

                    $success['url'] = Yii::app()->urlManager->createUrl('catalogue/create');
                    $success['catalogue'] = $this->renderPartial('_view', array('catalogues' => $catalogues), true);

                    echo json_encode($success);
                } else {
                    $error['Catalogue_name'] = 'Catalogue Already Exist';
                    echo json_encode($error);
                }
            } else {
                echo CActiveForm::validate($model);
            }

            exit;
        }

        $this->renderPartial('_create', array('model' => $model));
    }

    /**
     * Reviewed by _uJJwAL_
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Catalogue'])) {
            $model->attributes = $_POST['Catalogue'];
            $model->business_id = $this->business;

            if ($model->validate()) {
                $exist = Catalogue::model()->findByAttributes(array('business_id' => $this->business, 'name' => $model->name));
                if (empty($exist)) {
                    $model->save();

                    Yii::app()->user->setFlash('success', 'Successfully Edited Catalogue');

                    $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);

                    $success['url'] = Yii::app()->urlManager->createUrl('catalogue/update', array('id' => $model->id));
                    $success['catalogue'] = $this->renderPartial('_view', array('catalogues' => $catalogues), true);

                    echo json_encode($success);
                } else {
                    $error['Catalogue_name'] = 'Catalogue Already Exist';
                    echo json_encode($error);
                }
            } else {
                echo CActiveForm::validate($model);
            }

            exit;
        }

        $this->renderPartial('_create', array('model' => $model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $catalogue = $this->loadModel($id);
            $catalogue -> delete();
           
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'ajax' => 'true'));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($ajax = null) {
        $model = new Catalogue('search');
        $model->unsetAttributes();  // clear any default values

        $model -> business_id = $this -> business;

        if (isset($_GET['Catalogue']))
            $model->attributes = $_GET['Catalogue'];

        if ($ajax) {
            $this->renderPartial('_admin', array('model' => $model));
        } else {
            $this->renderPartial('admin', array('model' => $model));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Catalogue::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'catalogue-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @author _uJJwAL_
     * Retrieve Product List for Catalogue
     */
    public function actionProduct() {

        if (isset($_POST['catalogue'])) {
            $catalogue = $_POST['catalogue'];
            $catalogueArray = array_values(array_filter(explode(',', $catalogue)));
            Yii::app()->session['catalogues'] = $catalogueArray;
        } else {
            $catalogueArray = isset(Yii::app()->session['catalogues']) ? Yii::app()->session['catalogues'] : "";
        }

        if (isset($_POST['category'])) {
            $category = $_POST['category'];
            $categoryArray = array_values(array_filter(explode(',', $category)));
            Yii::app()->session['categories'] = $categoryArray;
        } else {
            $categoryArray = isset(Yii::app()->session['categories']) ? Yii::app()->session['categories'] : "";
        }

        if (isset($_POST['checked'])) {
            $checked = $_POST['checked'];
        }

        $categoryList = Categories::model()->findCategoryListByCatalogue($this->business);
        $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
        $productList = Products::model()->findProductByBusiness($this->business, $catalogueArray, $categoryArray);

        $categoryString = $this->renderPartial('_category', array('categories' => $categoryList), true);
        $catalogueString = $this->renderPartial('_view', array('catalogues' => $catalogues), true);
        $productListString = $this->renderPartial('_catalogue', array('productList' => $productList, 'checked' => $checked), true);

        $return['categories'] = $categoryString;
        $return['catalogues'] = $catalogueString;
        $return['product'] = $productListString;

        echo json_encode($return);
    }

    public function actionCatalogueList() {
        $catalogues = Catalogue::model()->findCatalogueByBusiness($this->business);
        $this->renderPartial('_view', array('catalogues' => $catalogues));
    }

    public function actionProcessCSV() {

        // Code to save csv file data
        if (isset($_POST['file']) && isset($_POST['type'])) {
            require_once(Yii::getPathOfAlias('documentroot') . '/protected/components/parsecsv.lib.php');
            $csv = new parseCSV();
            $file = Yii::getPathOfAlias('csvupload'). DIRECTORY_SEPARATOR . $_POST['file'];
            $csv->auto($file);
            $rowData = array();

            foreach ($csv->data as $key => $row) { //extract every row
                foreach ($row as $value) { //extract every column
                    array_push($rowData, $value);
                }
                $select = "SELECT id FROM `catalogues` WHERE product_name='$rowData[2]'";
                $command = Yii::app()->db->createCommand($select)->execute();
                $flag = 0;
                if ($command == 0) {
                    $model = new Catalogues;
                    $model->attributes = array('business_id', 'category_id', 'product_name', 'type', 'added_date', 'modified_date', 'modified_by', 'added_by', 'status');
                    $model->business_id = $rowData[0];
                    $model->category_id = $rowData[1];
                    $model->product_name = $rowData[2];
                    $model->type = 1;
                    $model->added_date = date('Y-m-d H:i:s');
                    $model->modified_date = date('Y-m-d H:i:s');
                    $model->modified_by = 0;
                    $model->added_by = Yii::app()->user->id;
                    $model->status = 'y';
                    $flag = $model->save();
                } else {
                    $model = Catalogues::model()->find('product_name = :product_name', array(':product_name' => $rowData[2]));
                    $model->attributes = array('business_id', 'category_id', 'product_name', 'type', 'added_date', 'modified_date', 'modified_by', 'added_by', 'status');
                    $model->business_id = $rowData[0];
                    $model->category_id = $rowData[1];
                    $model->product_name = $rowData[2];
                    $model->type = 1;
                    $model->added_date = date('Y-m-d H:i:s');
                    $model->modified_date = date('Y-m-d H:i:s');
                    $model->modified_by = 0;
                    $model->added_by = Yii::app()->user->id;
                    $model->status = 'y';
                    $flag = $model->save();
                }
                $rowData = array();
            }
            if ($flag) {
                Yii::app()->user->setFlash('success', 'Data Saved');
                $this->render('ProcessCSV', array('file_name' => $_POST['file']));
            } else {
                Yii::app()->user->setFlash('error', 'Invalid Data format.');
                $this->render('ProcessCSV', array('file_name' => $_POST['file']));
            }
        }

        if (isset($_FILES) && isset($_FILES['fle']['tmp_name']) && $_FILES['fle']['tmp_name'] != '') {
            $file_name = date('YmdHis') . '.csv';
            ini_set("auto_detect_line_endings", true);
            move_uploaded_file($_FILES["fle"]["tmp_name"], Yii::getPathOfAlias('csvupload') . DIRECTORY_SEPARATOR . $file_name);

            if ($_FILES["fle"]["type"] == 'text/csv') {
                $this->render('ProcessCSV', array('file_name' => $file_name));
            } else {
                Yii::app()->user->setFlash('error', 'Invalid File format.Only CSV files are allowed.');
                $model = new Catalogues;
                $catModel = new Category;
                $this->render('index', array(
                    'model' => $model,
                    'tags' => array(),
                    'catModel' => $catModel,
                ));
            }
        } else {
            
        }
    }

    /* public function actionLogs() {
      $this->layout = false;
      $sql = "SELECT * FROM logs WHERE file = {$_REQUEST['file']}";
      $logs = Yii::app()->db->createCommand($sql)->queryAll();
      $this->render('logs', array('logs' => $logs));
      }



      public function actionCategoryMove() {
      $this->layout = false;
      $command = Yii::app()->db;
      $sql = "SELECT * FROM categories WHERE business_id = 1 AND status = 'y'";
      $data = $command->createCommand($sql)->queryAll();
      $cats = array();
      foreach((array) $data as $cat) {
      $cats[$cat['id']] = $cat['title'];
      }
      $this->render('categorymove',array(
      'data' => $cats,
      ));
      }

      public function actionUpdateCatalogueCategory() {
      $msg = array();
      if(empty($_REQUEST['products'])) {
      $msg[] = 'Please select atleast one product.';
      }
      if(empty($_REQUEST['another_category'])) {
      $msg[] = 'Please select category.';
      }
      if(isset($_REQUEST['another_category']) && count($_REQUEST['another_category']) > 1) {
      $msg[] = 'Please select one category only.';
      }

      if(count($msg)) {
      echo json_encode(array('responseCode' => 'error', 'msg' => $msg));
      exit;
      } else {
      $command = Yii::app()->db;
      $sql = "UPDATE catalogues SET category_id = '".$_REQUEST['another_category'][0]."' WHERE id IN (".$_REQUEST['products'].")";
      $data = $command->createCommand($sql)->execute();
      LuceneManager::deleteLucene();
      echo json_encode(array('responseCode' => 'success'));
      exit;
      }
      } */

    /*
     * Displays the main upload CSV form
     */

    public function actionUploadCSV($category = null) {

        $detect = Yii::app()->mobileDetect;
        $isMobile = $detect->isMobile();


        $tableCode = "";
        $separator = "autodetect";
        $customSeparator = false;
        if ($isMobile && isset($_FILES["csvFile"])) {
            ob_start();
            $error = $this->actionCsvFileUpload(true);
            if (!$error) {
                $this->actionPreviewCSV(true, false);
                $tableCode = ob_get_clean();
                $separator = $_POST['separatorCharacter'];
                if (isset($_POST['customSeparatorCharacter']) && $_POST['customSeparatorCharacter'] != '') {
                    $separator = $_POST['customSeparatorCharacter'];
                    $customSeparator = true;
                }
            }
        }
        $this->title = Yii::t('csv-import', "Upload or Update your catalogue via CSV");
        $this->subtitle = Yii::t('csv-import', 'Add (and update) your catalogue or replace it totally with a brand new one');
        $this->headerLinks = array(Yii::app()->createUrl('catalogue') => Yii::t('csv-import', 'Back to catalogue'));
        $selectedCatalogues = array();
        $categoryObject = null;
        if ($category != null) {
            $categoryObject = Categories::model()->findByPk(intval($category));
            $categoryCatalogues = CategoryCatalogue::model()->findAllByAttributes(array('category_id' => $category));
            foreach ($categoryCatalogues as $cc)
                $selectedCatalogues[] = $cc->catalogue;
            /* if ($categoryObject != null)
              $selectedCatalogues = $categoryObject->catalogues; */
            /* if ($selectedCatalogues == null)
              $selectedCatalogues = array(); */
        }
        if (isset($_GET['catalogues'])) {
            $i = 0;
            while (isset($_GET['catalogues'][$i])) {
                $catalogueId = intval($_GET['catalogues'][$i]);
                $catalogue = Catalogue::model()->findByAttributes(array('business_id' => $this->business, 'id' => $catalogueId));
                if ($catalogue != null)
                    $selectedCatalogues[] = $catalogue;
                $i++;
            }
        }
        $this->render('uploadCSV', array(
            'categories' => Categories::model()->findAll(),
            'selectedCatalogues' => $selectedCatalogues,
            'category' => $categoryObject,
            'catalogues' => Users::model()->findByPk(Yii::app()->user->id)->getUserActiveCatalogues(),
            'isMobile' => $isMobile,
            'tableCode' => $tableCode,
            'separator' => $separator,
            'customSeparator' => $customSeparator,
            'error' => isset($error) ? $error: null,
        ));
    }

    /*
     * Called by AJAX, simply uploads the POSTed file
     */

    public function actionCsvFileUpload($isMobile = false) {
        if (!isset($_FILES['csvFile']))
            return 'Error: missing parameters!';
        $fileName = Yii::app()->user->getId() . '.csv';

        if (!in_array($_FILES["csvFile"]["type"], Yii::app()->params['csvMimetypes'])) {
            if ($isMobile)
                return Yii::t('csv-import', "Error: wrong format! Only CSV files are allowed!");
            else
                echo Yii::t('csv-import', "Error: wrong format! Only CSV files are allowed!");
        } else {
            move_uploaded_file($_FILES["csvFile"]["tmp_name"], Yii::getPathOfAlias('csvupload') . DIRECTORY_SEPARATOR . $fileName);
        }
        if ($_REQUEST['separatorCharacter'] == 'custom') {
            if (isset($_REQUEST['customSeparatorCharacter']))
                $separator = $_REQUEST['customSeparatorCharacter'];
            else
                $separator = ""; //won't work, user hasn't chosen any character
        }
        else
            $separator = $_REQUEST['separatorCharacter'];
        Yii::app()->session['delimiter'] = $separator;
        return null;
    }

    /*
     * Will be called through AJAX with the form that includes the file as POST, returns error or a HTML preview table
     * @showExistingFile: if not null, as alternative, call this method to retrieve the already uploaded CSV and parse that
     * @param offset: rows offset of the csv model (used for loading in chunks)
     * @param $offsetCSVLine: rows offset of the real csv (used for loading in chunks)
     * @param partialRender: if true, will output in chunks
     * @param mini: if true, displays a reduced version (8 rows)
     */

    public function actionPreviewCSV($mini = 0, $partialRender = 0, $offset = 0, $offsetCSVLine = 1 ) {
        
        if(!isset(Yii::app()->user))
            echo "not logged in";
       // ini_set('display_errors', 1);
        //  error_reporting(E_ALL);

        $rowsPerBlock = 60; //how many lines will this function return at a time
       // ob_start();
        //   var_dump($_FILES["csvFile"]);
        //$result = ob_get_clean();
//echo $result;exit;


        $fileName = Yii::app()->user->getId() . '.csv';

        if (isset($_REQUEST['separatorCharacter'])) {
            $separator = $_REQUEST['separatorCharacter'];
            if ($_REQUEST['separatorCharacter'] == 'custom') {
                if (isset($_REQUEST['customSeparatorCharacter']))
                    $separator = $_REQUEST['customSeparatorCharacter'];
                else
                    $separator = ""; //won't work, user hasn't chosen any character
            }
            else
                $separator = $_REQUEST['separatorCharacter'];
        }
        else {
                
                $separator = Yii::app()->session['delimiter'];
        }

        require_once(Yii::getPathOfAlias('documentroot') . '/protected/components/parsecsv.lib.php');
        $csv = new parseCSV();
        $file = Yii::getPathOfAlias('csvupload') . DIRECTORY_SEPARATOR . $fileName;
        if ($separator == 'autodetect') {
            ini_set("auto_detect_line_endings", true);
            $csv->auto($file);
        } else {
            $csv->delimiter = $separator;
            $csv->parseCSV($file);
        }
        Yii::app()->session['delimiter'] = $csv->delimiter; //store this, will be used later
        // $fieldsQty = null;
        $titles = CsvUpload::sanitizeHeaders($csv->titles);
        $htmlTable = "";//"<table class='spreadsheet-table ".($mini ? 'spreadsheet-mini':'')."'>\n";
        $line = $offsetCSVLine;
        //  foreach ($csv->data as $key => $row) { //extract every row
        $index = $offset;
        if ($partialRender)
            $max = min($index + $rowsPerBlock, count($csv->data));
        else
            $max = count($csv->data);
        for ($index; $index < $max; $index++) {
            $row = $csv->data[$index];
            if ($line == 1) {//first line
                // $fieldsQty = count($row);
                $htmlTable .= "<tr>\t<th>&nbsp;</th>\n";
                for ($i = 0; $i < (count($titles) + 1); $i++) {
                    if (($i / 26) <= 1)
                        $prefix = "";
                    else
                        $prefix =chr (64 + floor(($i -1) / 26));
                    if ($i > 0) { //skip first
                        $htmlTable .= "<th>$prefix" . chr(65 + (($i - 1) % 26)) . "</th>\n"; //populate with letters
                    }
                }
                $htmlTable .= "</tr>\n";
                //now the titles:
                $htmlTable .= "<tr>\t<th>1</th>\n";
                foreach ($csv->titles as $i => $t) {

                    $htmlTable .= "<td>" . utf8_encode($t) . "</td>\n";
                }
                $htmlTable .= "</tr>\n";
                $line++;
            }
             if(substr($row[$titles[0]],0,13) == '$ERASEDLINES$')
                {

                    $line += intval(substr($row[$titles[0]],14,3));
                    continue;
                }
            $htmlTable .= "<tr>\n";

            $htmlTable .= "<th>" . $line . "</th>";

            foreach ($titles as $c) {
                
                $htmlTable .= "<td>" . utf8_encode(isset($row[$c]) ? $row[$c] : '') . "</td>";
            }
            $htmlTable .= "</tr>\n";

            $line++;
            if ($mini && $line > 8)
                break;
        }
        if ($partialRender) {
            $htmlTable .= "\0"; //delimiter
            $percentage = intval($index / count($csv->data) * 100);
            $htmlTable .= "$index\0$line\0$percentage";
        }
        //  echo json_encode($rowData);
        //   $htmlTable .= "</table>\n";
        echo $htmlTable;
    }

    /*
     * Displays the whole CSV in a blank window
     */

    public function actionPreviewCSVStandalone() {
        ob_start();
        echo "<table id='csv_preview' class='spreadsheet-table'>";
        $this->actionCsvFileUpload();
        $this->actionPreviewCSV(false, false);
        echo "</table><br/>";
        $tableCode = ob_get_clean();
        
        $this->renderPartial('emptypage', array('content' => $tableCode));
        
    }

    public function actionMatchFields($category, $modality) {
        $detect = Yii::app()->mobileDetect;
        $isMobile = $detect->isMobile();
        $fileName = Yii::app()->user->getId() . '.csv';
        Yii::app()->session['uploadCSVModality'] = $modality; //
        Yii::app()->session['category'] = $category; //
        Yii::app()->session['catalogue'] = $_GET['catalogues']; //
        Yii::app()->session['csvProgress'] = 0;

        require_once(Yii::getPathOfAlias('documentroot') . '/protected/components/parsecsv.lib.php');
        $csv = new parseCSV();
        $file = Yii::getPathOfAlias('csvupload') . DIRECTORY_SEPARATOR . $fileName;
        $csv->delimiter = Yii::app()->session['delimiter'];
        $csv->parseCSV($file);
        $this->title = Yii::t('csv-import', "Match fields");
        $this->subtitle = Yii::t('csv-import', 'Match your file fields with ours');
        $this->headerLinks = array();
        $titles = CsvUpload::sanitizeHeaders($csv->titles);
        $titlesForDisplay = array();
        foreach ($titles as $i => $t) {
            if ($csv->titles[$i] == '') {
                $titlesForDisplay[$t] = "(" . Yii::t("csv-import", "empty header") . " #" . $i . ")";
            } else {
                $title = CatalogueController::limitStringLength($t, 63);
                $titlesForDisplay[$title] = $title;
            }
        }
        $previousMatches = Yii::app()->Cookies->getCMsg('matches');
        // Yii::app()->Cookies->putCMsg('matches', array());
        $this->render('choosefields', array('csvFields' => $titlesForDisplay,
            'previousMatches' => ($previousMatches == "Cookie not found" ? null : $previousMatches ),
            'category' => $category,
            'isMobile' => $isMobile
        /*  'catalogue' => $catalogue */        ));
    }

    /*
     * Auxiliary function that truncates long strings putting � at the end
     */

    public static function limitStringLength($string, $length) {
        if (strlen($string) > $length)
            return substr($string, 0, ($length - 1)) . '�';
        else
            return $string;
    }


    /*
     * Where the catalogue is effectively updated from the CSV located in /user-files
     * @param offset: what line should it start with?
     * @prgoressBar: if false, then no progress bar is being shown, so will send results through mail
     */

    public function actionDoUploadFromCSV($offset = 0, $offsetCSVLine = 2, $progressBar = false) {
        require_once(Yii::getPathOfAlias('documentroot') . '/protected/components/parsecsv.lib.php');
       /* $detect = Yii::app()->mobileDetect;
        $isMobile = false;//$detect->isMobile();*/
        set_time_limit ( 360 );        
        
        $percentageToUpdate = 5; //every 5%, update progress session variable
        
        $rowsPerBlock = 50; //how many lines will this function process at a time (only mobiles)
        
        $currentUser = Users::model()->findByPk(Yii::app()->user->id);
        
        $matches = CatalogueController::putMatchesInCookie();
        
        if ($offset == 0) {//only the first time
            Yii::app()->session['csvProgress'] = 1;

            

            $csvUpload = new CsvUpload;
            $csvUpload->user_id = $currentUser->id;
            $csvUpload->upload_time = new CDbExpression('NOW()');
            $csvUpload->save();
            
            $csvUploadId = $csvUpload->id;

            Yii::app()->session['imported'] = 0;
            Yii::app()->session['updated'] = 0;
            Yii::app()->session['rejected'] = 0;
        }
        else {
            $csvUploadId = Yii::app()->session['csvId'];
        }


        $fileName = Yii::app()->user->getId() . '.csv';

        $chosenCategory = Yii::app()->session['category'];
        $chosenCatalogue = Yii::app()->session['catalogue'] ;

        
        $csv = new parseCSV();
        $file = Yii::getPathOfAlias('csvupload') . DIRECTORY_SEPARATOR . $fileName;
        $csv->delimiter = Yii::app()->session['delimiter'];
        $csv->parseCSV($file);
        $totalLines = count($csv->data);
        $linesToUpdate = ceil($totalLines*$percentageToUpdate/100);

        /*if (Yii::app()->session['uploadCSVModality'] == 'add') {
            
        }*/
        

        $stats = array('imported' => 0, 'updated' => 0, 'rejected' => 0);
        $rejectedProducts = array();
        
        $cataloguesChosen = array();
        foreach ($chosenCatalogue as $c => $v) {
            $cataloguesChosen[] = Catalogue::model()->findByPk($c);
        }
        if (Yii::app()->session['uploadCSVModality'] == 'substitute' && $offset == 0) {//delete all!
            if ($chosenCategory == 'all')
            {
                ProductDetail::model()->deleteAllFromCatalogues($cataloguesChosen, true);
                Categories::model()->removeCategoriesOfCatalogues($cataloguesChosen);
            }
            else //one category
                ProductDetail::model()->deleteAllOnCategoryAndCataloguesFromUser($chosenCategory, $cataloguesChosen, $currentUser->id);
            Products::deleteAbsentProductsFromUser($currentUser->id);
        }
        //preload all the categories by this user:
        $categoriesResult = $currentUser->business->categories; //Categories::model()->findAll('added_by =' . $currentUser->id);
        $userCategories = array();
        if ($categoriesResult != null)
            foreach ($categoriesResult as $c) {
                $userCategories[trim(strtolower($c->name))] = $c;
            }
        $categoriesResult = null;
        if ($chosenCategory != 'all')
            $fixedCategory = Categories::model()->findByPk($chosenCategory);
        $titles = CsvUpload::sanitizeHeaders($csv->titles);
        /*         * ***************************************** */
        /*         * *************MAIN LOOP ****************** */
        /*         * ***************************************** */
        $line = $offsetCSVLine;
        if ($offset == 0)
            Yii::app()->session['csvProgress'] = 2;
        if($isMobile)
            $max = min($offset + $rowsPerBlock,$totalLines);
        else
            $max = $totalLines;

        for($j = $offset; $j < $max; $j++){ //extract every row
            $row = $csv->data[$j];
            session_write_close(); 
            if (substr($row[$titles[0]], 0, 13) == '$ERASEDLINES$') {

                $line += intval(substr($row[$titles[0]],14,3));
                    continue;
                }
            $skipThis = false;
            //      $productAdded = false;
            //      $customFieldValues = array(); //will store temporarily field name => value
            //  $rowData = array();
            $hasContent = false;
            //$currentCategory = null;
            $specialFailure = null;


            foreach ($row as $value) { //extract every column
                //  array_push($rowData, $value);
                if ($value != '') {
                    $hasContent = true;
                    break;
                }
            }
            if (!$hasContent)//empty row
            {
                $line++;
                continue;
            }
            $newProduct = null;
            if ($chosenCategory == 'all') {//retrieve categories from CSV
                $categoryNameInRow = strtolower(CatalogueController::sanitizeNameForDatabase($row[$matches['category']],Yii::app()->params['maxChars']['category']));
                if (isset($userCategories[$categoryNameInRow])) {
                    // $currentCategory = $userCategories[$categoryNameInRow];
                    $category_id = $userCategories[$categoryNameInRow]->id;
                } else {
                    //    $currentCategory = null;
                    $category_id = null;
                }
            } else {//retrieve previously chosen
                if (!isset($matches['category']))
                    $category_id = $chosenCategory;
                else
                    $category_id = null;
            }

            $retrieveCriteria = new CDbCriteria();
            $retrieveCriteria->addCondition('LOWER(name) = LOWER(:name)');
            $retrieveCriteria->addCondition('added_by = ' . intval($currentUser->id));
            $retrieveName = CatalogueController::sanitizeNameForDatabase($row[$matches['name']], Yii::app()->params['maxChars']['product']);
            $retrieveCriteria->params = array(':name' => $retrieveName);

            $matchingProducts = Products::model()->findAll($retrieveCriteria);
            //now retrieve product details for these categories, if they exist
            //  }
            if (count($matchingProducts) > 0) {
                foreach ($matchingProducts as $mp) {
                    //among all products with the same name, is there one with the same category? (sorry, this should be a SQL condition)
                    if (count($mp->productDetails) > 0)
                        if ($mp->productDetails[0]->category_id == $category_id) {
                            $newProduct = $mp;
                            break;
                    }
                }
            }
            $matchingProducts = null;

            $productIsNew = ($newProduct == null);
            if ($newProduct == null)
                $newProduct = new Products;
            /* if ($matches['catalogue'] == 'none' || !isset($matches['catalogue'])) { */

            $chosenCataloguesIds = array();
            foreach ($chosenCatalogue as $c => $v) {
                $chosenCataloguesIds[] = $c;
            }
            $cataloguesTargeted = Catalogue::model()->findCatalogueByCategoryAndIds($chosenCategory == 'all' ? null : $chosenCategory, $chosenCataloguesIds);
            //must create product details for each catalogue:
            $newProductDetails = array();
            foreach ($cataloguesTargeted as $i => $ct) {
                /* if ($i == 0)//first one already exists
                  $newProductDetails[0]->catalogue_id = $ct->id;
                  else { */
                $productDetails = ProductDetail::model()->findByAttributes(array('catalogue_id' => $ct->id, 'product_id' => $newProduct->id));
                if ($productDetails == null) {
                    $productDetails = new ProductDetail;
                    $productDetails->catalogue_id = $ct->id;
                    $productDetails->description = " ";
                    $productDetails->category_id = $category_id;
                    // $productDetails->product_id = $newProduct->id;
                } else {
                    //update existing
                    if ($category_id != null)
                        $productDetails->category_id = $category_id;
                    if (isset($matches['other1']))
                        $productDetails->description = ""; //there are 'other' fields to be put in description, so it will be overwritten
                }
                $newProductDetails[] = $productDetails;
                //   }
            }
            /*      } else {
              //retrieve all from previous choices
              $cataloguesTargeted = $cataloguesChosen;
              } */

            foreach ($row as $field => $value) {//process each value
                if (strval($field) == $matches['name']) {
                    ////name
                    if ($value != '') {
                        if (!isset($newProduct->name)) {//adding this:
                            $newProduct->name = CatalogueController::sanitizeNameForDatabase($value, 80);
                            $newProduct->added_by = $currentUser->id;
                            $newProduct->added_date = new CDbExpression('NOW()');
                            $newProduct->save();
                        }
                    }
                    foreach ($newProductDetails as $npd) {
                        if (!isset($newProduct->id))//product wasn't saved, will force error
                            $npd->product_id = null;
                        else
                            $npd->product_id = $newProduct->id;
                    }
                }
                /*   elseif (strval($field) === $matches['catalogue']) {
                  ////catalogue
                  if ($value == '')
                  continue; //skip field


                  //check if included in targetted catalogues:
                  $catalogueIncluded = false;
                  foreach ($cataloguesTargeted as $i => $ct) {
                  $catalogueName = Yii::app()->filterWord->replacement(utf8_encode(trim(strtolower($value))));
                  if (strtolower($ct->name) == $catalogueName) {
                  $catalogueIncluded = true; //save the index
                  foreach ($newProductDetails as $npd) {
                  $npd->catalogue_id = $ct->id;
                  }
                  }
                  }
                  if (!$catalogueIncluded)
                  $skipThis = true;
                  } */
                elseif (strval($field) == $matches['category']) {
                    if ($value == '')
                        continue; //skip field
////category 
                  //  echo utf8_encode(substr($value,0,1)).": ".CatalogueController::convert_smart_quotes($value)." ".ord(utf8_encode(substr($value,0,1)));
                    $categoryName = CatalogueController::sanitizeNameForDatabase($value,Yii::app()->params['maxChars']['category']);
                    $categoryNameLowerCase = strtolower($categoryName);
                    if (($chosenCategory == 'all' || strtolower($fixedCategory->name) == $categoryNameLowerCase)) {
                        //  $category = Categories::model()->find(array('UPPER(name) = UPPER(:name)',''), array(':name' => trim($rowData[$i])));

                        if (!isset($userCategories[$categoryNameLowerCase])) {//must create
                            $newCategory = new Categories;
                            $newCategory->name = $categoryName;
                            $newCategory->type = 1;
                            $newCategory->added_by = $currentUser->id;
                            $newCategory->type = 1;
                            $newCategory->added_date = new CDbExpression('NOW()');
                            if (!$newCategory->save())
                                $specialFailure = Yii::t("csv-import", "Name for category is too long!");;


                            $userCategories[$categoryNameLowerCase] = $newCategory;

                            foreach ($newProductDetails as $npd) {
                                $npd->category_id = $newCategory->id;
                            }
                        } else {
                            $newCategory = $userCategories[$categoryNameLowerCase];
                            foreach ($newProductDetails as $npd) {
                                $npd->category_id = $newCategory->id;
                            }
                        }
                        foreach ($cataloguesTargeted as $ct) {
                            //put category in all catalogues:

                            $newCategory->assignToCatalogue($ct->id);
                        }
                    } elseif ($chosenCategory != 'all' && strtolower($fixedCategory->name) != $categoryNameLowerCase)
                        $skipThis = true;
                }
                elseif (strval($field) == $matches['link']) {
                    ////link
                    if ($value != '') {
                        foreach ($newProductDetails as $npd) {
                            $npd->link = $value;
                        }
                    }
                } elseif (strval($field) == $matches['price']) {
                    ////price
                    if ($value != '') {

                        /*  preg_match("/([\$\�\�])?\s?(\d*(\.|\,)?(\d+){0,2})/", utf8_encode($value), $results, null, 0);
                          if ($results[0] != '') { */
                        $price = ProductDetail::getPriceOfString(utf8_encode($value));
                        foreach ($newProductDetails as $npd) {
                            $npd->price = $price;
                        }
                        //break;
                        // }
                    }
                } else {
                    ////custom field
                    //check if it was selected:
                    $i = 1;
                    while (isset($matches["other$i"])) {
                        if ($matches["other$i"] == $field) {
                            $field = CatalogueController::sanitizeNameForDatabase($field, 80);
                            $value = CatalogueController::sanitizeNameForDatabase($value, 1024);
                            //it is
                            foreach ($newProductDetails as $npd)
                                $npd->description .= Yii::app()->filterWord->replacement(utf8_encode("$field: $value\n\n"));
                            break;
                        }
                        $i++;
                    }
                }
            }
            if ($skipThis)
            {
                $line++;
                continue;
            }

            if (!$newProductDetails[0]->validate()) {
                //rejected!
                $stats['rejected']++;
                $newRejectedProduct = new ProductRejectedImport;
                $newRejectedProduct->csv_upload_id = $csvUploadId;
                $newRejectedProduct->line = ($line );
              /*  if (isset($newProduct->id))
                    $newRejectedProduct->id = $newProduct->id;
                else
                    $newRejectedProduct->id = null;*/
                foreach ($newProductDetails[0] as $property => $value) {
                    if ($property != 'tags' && $property != 'id')
                        $newRejectedProduct->$property = $value;
                }
                $newRejectedProduct->added_by = $currentUser->id;
                $newRejectedProduct->added_date = new CDbExpression('NOW()');
                $newRejectedProduct->save();
                if ($specialFailure != null)
                    $newRejectedProduct->specialFailure = $specialFailure;
                $rejectedProducts[($line )] = $newRejectedProduct;
            } else {
                //accepted!
                //official save:
                foreach ($newProductDetails as $npd)
                    $npd->save();
                if ($productIsNew)
                    $stats['imported']++;
                else
                    $stats['updated']++;
                /*      foreach ($customFieldValues as $name => $value) {
                  $customFieldValue = new ProductCustomValue;
                  $customFieldValue->custom_field_id = $customFields[$name];
                  $customFieldValue->product_id = $newProduct->id;
                  $customFieldValue->value = utf8_encode(Yii::app()->filterWord->replacement($value));
                  $customFieldValue->save();
                  } */
            }
            session_start();
            if ($line % $linesToUpdate == 0) {
                //update progress
                Yii::app()->session['csvProgress'] = min(floor($line / $totalLines * 100),100);
            }
            $line++;
        }
        //IE forces me to use session variables because it doesn't recognize the AJAX response
        if ($j == $totalLines)
            Yii::app()->session['csvProgress'] = 100;
        Yii::app()->session['imported'] = Yii::app()->session['imported'] + $stats['imported'];
        Yii::app()->session['updated'] = Yii::app()->session['updated'] + $stats['updated'];
        Yii::app()->session['rejected'] = Yii::app()->session['rejected'] + $stats['rejected'];
        Yii::app()->session['csvId'] = $csvUploadId;
        if (!$progressBar) {
            $email = new Email();
            $urlParameters = array('imported' => Yii::app()->session['imported'], 'updated' => Yii::app()->session['updated'], 'rejected' => Yii::app()->session['rejected'], 'csv' => $csvUploadId, 'separator' => Yii::app()->session['delimiter']);

            $cataloguesQueryString = "";
            $i = 1;
            foreach (Yii::app()->session['catalogue'] as $c => $v) {
                $urlParameters['catalogue' . $i] = $c;
            }
            $body = Yii::t("csv-import", 'Your csv has been processed. You can see the results <a href="{url}">here</a>.', array('{url}' => (Yii::app()->createAbsoluteUrl('catalogue/showCSVResults', $urlParameters) . $cataloguesQueryString)));
            $email->sendEmail("noreply@nirbuy.co.uk", $currentUser->email, Yii::t('csv-import', "Upload results"), $body);
        }
        if ($isMobile && Yii::app()->session['csvProgress'] < 100)
        //$this->render('csvProgress', array('progress' => Yii::app()->session['csvProgress'], 'offset' => $i, 'offsetCSVLine' => $line, 'isMobile' => true));
            echo Yii::app()->session['csvProgress'] . "|$j|$line";
        else
            echo "100|" . Yii::app()->session['imported'] . '|' . Yii::app()->session['updated'] . '|' . Yii::app()->session['rejected']. '|' . $csvUploadId;
    }

    public function actionShowCSVResults($imported, $updated, $rejected, $csv) {
        if (isset($_GET['catalogue1'])) {
            $i = 1;

            $catalogues = array();
            while (isset($_GET['catalogue' . $i])) {
                $catalogues[intval($_GET['catalogue' . $i])] = 1;
                $i++;
            }
            Yii::app()->session['catalogue'] = $catalogues;
        }
        if(isset($_GET['separator']))
        {
            Yii::app()->session['delimiter'] = $_GET['separator'];
        }
        $csvUpload = CsvUpload::model()->findByPk($csv);
        $this->title = Yii::t('csv-import', "Upload results");
        $this->subtitle = Yii::t('csv-import', 'If a few records failed to be imported, you can always add them manually');
        $this->headerLinks = array();
        $detect = Yii::app()->mobileDetect;
        $isMobile = $detect->isMobile();
        $this->render('importresults', array(
            'stats' => array('imported' => $imported, 'updated' => $updated, 'rejected' => $rejected),
            'rejectedProducts' => $csvUpload->productsRejected,
            'CSVUploadId' => $csvUpload->id,
            'isMobile' => $isMobile,
        ));
    }

    /* public function beforeAction($action) {
      ob_start();
      if($action->id == 'doUploadFromCSV')
      $this->matches = CatalogueController::putMatchesInCookie();
      return parent::beforeAction($action);
      } */

    //stores all the field matches (taken from POST) into a cookie, returns a matches array
    public static function putMatchesInCookie() {
        ob_start();
        //save matches in cookie
        $matches = array();
        if (isset($_REQUEST['category']))
            $matches['category'] = $_REQUEST['category'];
        else
            $matches['category'] = null;
       /* if (isset($_POST['catalogue']))
            $matches['catalogue'] = $_POST['catalogue'];
        else
            $matches['catalogue'] = null;*/
        $matches['name'] = $_REQUEST['name'];
        if ($_POST['link'] != 'none')
            $matches['link'] = $_REQUEST['link'];
        if ($_POST['price'] != 'none')
            $matches['price'] = $_REQUEST['price']; //filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $i = 1;
        $enteredIndex = 1;
        while (isset($_REQUEST['other' . $i])) {
            if ($_REQUEST['other' . $i] != 'none')
            {
                $matches['other' . $enteredIndex] = $_REQUEST['other' . $i];
                $enteredIndex++;
            }
            $i++;
        }
        Yii::app()->Cookies->putCMsg('matches', $matches);
        return $matches;
    }

    /*
     * Creates in the database all custom fields that don't exist and are present in the POST, returns an array of custom field objects
     */

    public static function createNewCustomFields() {
        //create all custom fields that don't exist
        $i = 1;
        $customFields = array();
        while (isset($_POST['other' . $i])) {
            if ($_POST['other' . $i] != 'none') {
                $customField = ProductCustomField::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'name' => $_POST['other' . $i]));
                if ($customField == null) {
                    //create
                    $customField = new ProductCustomField;
                    $customField->user_id = Yii::app()->user->id;
                    $customField->name = $_POST['other' . $i];
                    $customField->save();
                }
                $customFields[$customField->name] = $customField->id; //dictionary of custom fields to be used below
            }
            $i++;
        }
        return $customFields;
    }

    /*
     * Used inside CSV process. Creates a CDBCriteria to retrieve all the products set by session variables
     * @currentUser: User object belonging to the user currently logged in
     * @return CDBCriteria object 
     */

 /*   public static function createRetrieveCriteriaForUser($currentUser) {
        $retrieveCriteria = new CDbCriteria();

        return $retrieveCriteria;
    }
*/
    /*
     * Shows screen that allows to fix rejected rows from csv import
     */

    public function actionFixRejectedCSV($id) {
        $currentUser = Users::model()->findByPk(Yii::app()->user->id);
        $detect = Yii::app()->mobileDetect;
        $isMobile = $detect->isMobile();
        if (isset($_POST['id0']))
            $stats = CatalogueController::uploadFixedRejected($currentUser);
        else
            $stats = array();
        $csvUpload = CsvUpload::model()->findByPk($id);

        $this->title = Yii::t('csv-import', "Upload or Update your catalogue via CSV");
        $this->subtitle = Yii::t('csv-import', 'Add (and update) your catalogue or replace it totally with a brand new one.');
        $this->headerLinks = array(Yii::app()->createUrl('catalogue') => Yii::t("csv-import", "Back to Catalogue"));
        $this->render('fixRejectedCSV', array(
            'rejectedRows' => $csvUpload->productsRejected,
            'catalogues' => $currentUser->getUserActiveCatalogues(),
            'categories' => $currentUser->getUserActiveCategories(true),
            'stats' => $stats,
            'isMobile' => $isMobile,
        ));
    }

    /*
     * Process the POST form to upload rejected rows from CSV that have been fixed
     * @currentUser: Users istance with the user currently logged in
     * returns an array of stats with imported, updated and rejected numer of rows
     */

    public static function uploadFixedRejected($currentUser) {
        $stats = array('imported' => 0, 'updated' => 0, 'rejected' => 0);
        $i = 0;
        while (isset($_POST['id' . $i])) {
            if (isset($_POST["name$i"]) && $_POST["name$i"] == '') {
                $i++;
                continue; //name not entered
            }
            $currentCategory = null;
            $rejectedProduct = ProductRejectedImport::model()->findByPk($_POST['id' . $i]);
            $productCriteria = new CDbCriteria;

            $productCriteria->addCondition('LOWER(name) = :name');
            if (isset($_POST["name$i"])) {
                $newProductName = CatalogueController::sanitizeNameForDatabase($_POST['name' . $i],Yii::app()->params['maxChars']['product']);
                $productCriteria->params = array(':name' => strtolower($newProductName));
            }
            else
                $productCriteria->params = array(':name' => strtolower($rejectedProduct->product->name));
            $productCriteria->addCondition('added_by = ' . intval(Yii::app()->user->id));
            $product = Products::model()->find($productCriteria);
            
            if (isset($_POST['newcategory' . $i]) && $_POST['newcategory' . $i] != '') {

                $categoryName = CatalogueController::sanitizeNameForDatabase($_POST['newcategory' . $i],Yii::app()->params['maxChars']['category']);
                $criteria = new CDbCriteria;
                $criteria->addCondition('added_by = ' . intval(Yii::app()->user->id));
                $criteria->addCondition('LOWER(name) = "' . strtolower($categoryName).'"');
                $currentCategory = Categories::model()->find($criteria);
                if ($currentCategory == null) {
                    //I guess we'll have to create it...
                    $currentCategory = new Categories;
                    $currentCategory->name = $categoryName;
                    $currentCategory->added_by = $currentUser->id;
                    $currentCategory->type = 1;
                    $currentCategory->added_date = new CDbExpression('NOW()');
                    $currentCategory->save();
                }
            } elseif (isset($_POST['category' . $i]) && $_POST['category' . $i] != '-(none)-') {
                $currentCategory = Categories::model()->findByPk($_POST['category' . $i]);

                // $product->category_id = intval($_POST['category' . $i]);
            }
            
            if ($product != null && count($product->productDetails) > 0)
                if ($product->productDetails[0]->category_id != $currentCategory->id)
                    $product = null; //not the same category, it's another product then
            
            if ($product == null) {
                $created = true;
                $product = new Products;
                $product->name = $newProductName;
                $product->added_by = intval(Yii::app()->user->id);
                $product->added_date = new CDbExpression('NOW()');
                $product->save();
            }
            else
                $created = false;
            //copy all rejected to current product
            $productDetails = array(new ProductDetail);
            $productDetails[0]->product_id = $product->id;
            $chosenCataloguesIds = array();
            foreach (Yii::app()->session['catalogue'] as $c => $v) {
                $chosenCataloguesIds[] = $c;
            }
            $cataloguesTargeted = Catalogue::model()->findCatalogueByCategoryAndIds(Yii::app()->session['category'] == 'all' ? null : Yii::app()->session['category'], $chosenCataloguesIds);
            //must clone the product for each catalogue:
            foreach ($cataloguesTargeted as $j => $ct) {
                if ($j == 0)//first one already exists
                    $productDetails[0]->catalogue_id = $ct->id;
                else {
                    $clonedProductDetails = clone $productDetails[0];
                    $clonedProductDetails->catalogue_id = $ct->id;
                    $productDetails[] = $clonedProductDetails;
                }
            }

            foreach ($rejectedProduct as $attribute => $value) {
                if ($attribute != 'line' && $attribute != 'csv_upload_id' && $attribute != 'id' && $attribute != 'added_by' && $attribute != 'added_date' && $attribute != 'catalogue_id') {
                    foreach ($productDetails as $pd) {
                        if ($value != null)
                            $pd->$attribute = $value;
                    }
                }
            }

            
            if (isset($currentCategory)) {
                //a category was chosen, update everything
                foreach ($productDetails as $pd) {
                    $pd->category_id = $currentCategory->id;
                    $currentCategory->assignToCatalogue($pd->catalogue_id);
                }
                //also the category itself:
                
            }
            /*   if (isset($_POST['catalogue' . $i]) && $_POST['catalogue' . $i] != '-(none)-') {
              $product->catalogue_id = intval($_POST['catalogue' . $i]);
              } */
            if ($productDetails[0]->validate()) {
                //save all
                foreach ($productDetails as $pd) {
                    $pd->save();
                }
                if ($created)
                    $stats['imported']++;
                else
                    $stats['updated']++;
                //transfer all custom values

                $rejectedProduct->delete();
            }
            else {
                $stats['rejected']++;
            }
            $i++;
        }
        return $stats;
    }
    
    function actionShowCSVProgress() {
        $detect = Yii::app()->mobileDetect;
        $isMobile = false;//$detect->isMobile();
       /* if ($isMobile && intval(Yii::app()->session['csvProgress']) == 100) {
            //redirect, finished
            $this->redirect(Yii::app()->createUrl('catalogue/showCSVResults', array('imported' => Yii::app()->session['imported'], 'updated' => Yii::app()->session['updated'], 'rejected' => Yii::app()->session['rejected'], 'csv' => Yii::app()->session['csvId'])));
            exit;
        }*/
        $this->title = Yii::t('csv-import', Yii::t("csv-import", "Importing from CSV"));
        $this->subtitle = Yii::t('csv-import', 'Add (and update) your catalogue or replace it totally with a brand new one.');
        $this->headerLinks = array(Yii::app()->createUrl('catalogue') => Yii::t("csv-import", "Back to Catalogue"));
        $this->render('csvProgress', array(
            'isMobile' => $isMobile,
            'progress' => intval(Yii::app()->session['csvProgress']),
        ));
    }
    
    function actionGetCurrentCSVProgress() {
        if (!isset(Yii::app()->session['csvProgress']))
            echo 0;
        elseif (intval(Yii::app()->session['csvProgress']) == 100)
            echo Yii::app()->session['csvProgress'] . "|" . Yii::app()->session['imported'] . '|' . Yii::app()->session['updated'] . "|" . Yii::app()->session['rejected'] . "|" . Yii::app()->session['csvId'];
        else
            echo Yii::app()->session['csvProgress'];
    }
    
    /*
     * Removes extra spaces, enclosing quotes, bad words, encodes to utf-8... 
     * @param string: the text to format
     * @param maxLength: maximum length, will truncate if longer
     */
    public static function sanitizeNameForDatabase($string, $maxLength) {
        $search = array(chr(145),
            chr(146),
            chr(147),
            chr(148),
            chr(151));

        $replace = array("'",
            "'",
            '"',
            '"',
            '-');

        return CatalogueController::limitStringLength(utf8_encode(Yii::app()->filterWord->replacement(trim(str_replace($search, $replace, $string)," \t\n\r\0\x0B\""))), $maxLength); 
}

 public function actionGetFileProgress($fileid) {
        if (!extension_loaded('apc') || !ini_get('apc.enabled'))
        {
            echo 100;
            exit;
        }
        $status = apc_fetch('upload_' . $fileid);
        if ($status['total'] > 0)
            echo ceil($status['current'] / $status['total'] * 100);
        else
            echo 0;
    }
    
    public function actionConfirmcatalogue()
    {
        $catalogues = Catalogue::model()->findCatalogueByBusiness(Yii::app()->session['business']);
        foreach($catalogues as $catalogue)
        {
            $catalogue->last_modified = date('Y-m-d H:i:s');
            $catalogue->save();
        }
        Yii::app()->user->setFlash('catalogue_confirmed', "Your catalogue has been confirmed");
        $this->redirect(Yii::app()->createUrl('business/overview'));
    }
    
    public function actionPublishcatalogue()
    {
        $catalogues = Catalogue::model()->findCatalogueByBusiness(Yii::app()->session['business']);
        foreach($catalogues as $catalogue)
        {
            $catalogue->published = 1;
            $catalogue->save();
        }
        Yii::app()->user->setFlash('catalogue_published', "Your catalogue has been published");
        $this->redirect(Yii::app()->createUrl('business/overview'));
    }

}
