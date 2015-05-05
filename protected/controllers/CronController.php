<?php

class CronController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionEmail() {
        $emailQueue = EmailQueue::model()->findAll("email_status = '0'");
        if (is_array($emailQueue)) {
            foreach ($emailQueue as $emailQueue) {
                $count++;
                $email = new Email();
                if ($email->sendQueuedMail($emailQueue)) {
                    $emailQueue->email_status = 1;
                    $emailQueue->email_sent_date = date("Y-m-d H:i:s");
                    $emailQueue->save();
                }
                if ($count > 50) {
                    die();
                }
            }
        }
    }

    /*
     * Performs maintenance operations, called every week
     */

    public function actionWeeklyMaintenance() {
        //delete 2-days old csvs:
        $files = glob(Yii::getPathOfAlias('csvupload') . "/*.csv");
        $time = time();
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($time - filemtime($file) >= 60 * 60 * 24 * 2) // 2 days
                    unlink($file);
            }
        }
        CsvUpload::model()->deleteAll(array('condition' => 'upload_time < DATE_SUB(NOW(), INTERVAL 2 DAY)'));
        echo "Tasks performed!";
    }

}
