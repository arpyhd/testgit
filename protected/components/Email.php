<?php
class Email
{
    protected $name;
    protected $From;
    protected $to;
    protected $subject;
    protected $body;
    
    
    public function sendQueuedMail($queue)
    {
        $from = $queue->email_from;
        $to = $queue->email_to;
        $subject = $queue->email_title;
        $body = $queue->email_content;
        
        Yii::import('application.extensions.mail.swiftmailer.lib.classes.Swift', true);
        Yii::registerAutoloader(array('Swift', 'autoload'));
        Yii::import('application.extensions.mail.swiftmailer.lib.swift_init', true);
        
        $transport = Swift_MailTransport::newInstance();

        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($subject)->setFrom($from)->setTo($to)->setBody($body)->addPart(nl2br($body), 'text/html');

        // Send the message
        $result = $mailer->send($message);
        return $result;
    }
    
    
    /*
     * send email directly rather than by generating email from templates
     */

    public function sendEmail($from, $to, $subject, $body){
        Yii::import('application.extensions.mail.swiftmailer.lib.classes.Swift', true);
        Yii::registerAutoloader(array('Swift', 'autoload'));
        Yii::import('application.extensions.mail.swiftmailer.lib.swift_init', true);
        $transport = Swift_MailTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($subject)->setFrom($from)->setTo($to)->setBody($body, 'text/html');
        $result = $mailer->send($message);
        return $result;
    }

}
?>