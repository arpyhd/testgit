<?php

class ProfileController extends Controller
{
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/internal-yiibootstrap';

	/**
	 * Profile Page
	 */
	public function actionIndex()
	{	
		$model = Users::model()->findByPk(Yii::app()->user->getId());		

		if(isset($_POST['Users'])) {
			$model -> attributes = $_POST['Users'];

			if($model -> save(false)) {
				Yii::app()->user->setFlash('success', 'Profile Updated Successfully');
			}
		}

		$this->render('index',array('model' => $model));
	}
	
	/**
	 * Reviewed by _uJJwAL_
	 */
	public function actionChangePassword($id){
		$model = Users::model()->findByPk($id);
		$password = $model -> password;

		if(isset($_POST['Users'])){		
			$model -> scenario = 'changepassword';
			$model -> attributes = $_POST['Users'];
			
			if($model -> validate()) {
				if($password == md5($model -> current_password)) {
					$model -> password = md5($model -> new_password);
					if($model -> save()) {
						Yii::app()->user->setFlash('success', 'Password Changed Successfully!');
					} 
				} else {
					Yii::app()->user->setFlash('error', 'Invalid Current Password');
				}
			}
		}

		$this->render('changepassword', array('model' => $model));		
	}
    
    /**
	 * Reviewed by _uJJwAL_
	 */ 
    public function actionHistory() {
        if(Yii::app()->user->id) {
            $model=new FavouriteHistory;
            if(isset($_REQUEST['Favourite'])) {
	            $model->attributes=$_REQUEST['Favourite'];
	            $model->business_name=$_REQUEST['Favourite']['business_name'];
	            $model->location_name=$_REQUEST['Favourite']['location_name'];
            }

			if($_REQUEST['ajax']){
        		$this->renderPartial("history",array("model"=>$model));
			} else {
				$this->render("history",array("model"=>$model));
			}		
        } else {
            $this->redirect(Yii::app()->createAbsoluteUrl("site/login"));
        }        
    }

    /**
	 * Reviewed by _uJJwAL_
	 */ 
    public function actionFavourite() {
        if(Yii::app()->user->id) {
            $model=new Favourite;
            
            if(isset($_REQUEST['Favourite'])) {
            	$model->attributes=$_REQUEST['Favourite'];
            	$model->business_name=$_REQUEST['Favourite']['business_name'];
            	$model->location_name=$_REQUEST['Favourite']['location_name'];
            }

        	$this->render("favourite",array("model"=>$model));
        } else {
            $this->redirect(Yii::app()->createAbsoluteUrl("site/login"));
        }
    }

    public function actionFavouritecancel()
    {
        if(Yii::app()->user->id)
        {
            if(isset($_REQUEST['fid']))
            {
                foreach ($_REQUEST['fid'] as $fid)
                {
                    $model=Favourite::model()->findbyPK($fid);
                    $model->favourite=0;
					$model->status=0;
                    $model->save(false);
                }
            }

        }
    }
	public function actionMarkfavourite()
	{
		if($_REQUEST['hid'])
		{
			$hid=$_REQUEST['hid'];
			$model=FavouriteHistory::model()->findbyPk($hid);
			$fid=$model->Favourite->id;
			$modelF=Favourite::model()->findbyPk($fid);
			
			$modelF->favourite=1;
			$modelF->activity_status=0;
			$modelF->status=1;
			$modelF->save();
			echo $modelF->countFav();
		}
		if($_REQUEST['id'])
		{
			$fid=$_REQUEST['id'];
			
			$modelF=Favourite::model()->findbyPk($fid);
			
			$modelF->favourite=1;
			$modelF->status=1;
			$modelF->save();
			echo $modelF->countFav();
		}
		
	}
	public function actionMarkunfavourite()
	{
		
		
		if($_REQUEST['hid'])
		{
			$hid=$_REQUEST['hid'];
			$model=FavouriteHistory::model()->findbyPk($hid);
			$fid=$model->Favourite->id;
			$modelF=Favourite::model()->findbyPk($fid);
			
			$modelF->favourite=0;
			$modelF->activity_status=0;
			$modelF->status=0;
			$modelF->save();
			echo $modelF->countFav();
		}
		if($_REQUEST['id'])
		{
			$fid=$_REQUEST['id'];
			
			$modelF=Favourite::model()->findbyPk($fid);
			
			$modelF->favourite=0;
			$modelF->status=0;
			 $modelF->save();
			 echo $modelF->countFav();
		}
		
	}
}