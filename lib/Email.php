<?php
//dengjing34@vip.qq.com
class Email extends Smtp {    

    function __construct() {
        $this->relay_host = "smtp.exmail.qq.com";
        $this->smtp_port = 25;
        $this->auth = true; // if need auth
        $this->user = "report@84fun.com";
        $this->pass = "dengjing4234";
        parent::__construct($this->relay_host, $this->smtp_port, $this->auth, $this->user, $this->pass);
    }
    
    public function send($to, $subject, $body, $cc = '', $mailtype = 'HTML') {
        $this->sendmail($to, $this->user, $subject, $body, $mailtype, $cc);
    }
}
?>
