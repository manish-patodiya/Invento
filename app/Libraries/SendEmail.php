<?php
namespace App\Libraries;

class SendEmail
{
    public function __construct()
    {
        $this->email = \Config\Services::email();
        $config['protocol'] = 'sendmail';
        $config['mailPath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordWrap'] = true;
        $config['mailType'] = "html";
        $config['SMTPPort'] = 465;

        $this->email->initialize($config);
    }

    public function send($email, $subject, $body)
    {
        if ($email && $body) {
            $this->email->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $this->email->setTo($email);
            $this->email->setSubject($subject);
            $this->email->setMessage($body);
            $this->email->send(false);
            return true;
            // echo $this->email->printDebugger(['headers', 'subject', 'body']);
        } else {
            return false;
        }
    }
}