<?php

/*The file will be able to send an email to the client*/
include_once("Includes/PHPMailer/PHPMailerAutoload.php");


include_once("interfaces/TryUpdate.php");

class TrySendEmail  implements TryUpdate{
    
    private $from=null;
    private $to =null;
    private $message;
    private $mailer=null;
    private $subject=null;
    private $name=null;
    
    function __construct($message, $from, $to ,$name,$subject="DEvent Administrator",$withHtml=true) {
        $this->message=$message;
        $this->from=$from;
        $this->name=$name;
        $this->to=$to;
        $this->subject=$subject;
        $this->mailer= new  PHPMailer();
        $this->initMailer($withHtml);
     
        
    }
    
    private function initMailer($withHtml){
        if($this->mailer !=null)
       {
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug=0;
        $this->mailer->SMTPAutoTLS = false;
        $this->mailer->Debugoutput = 'html';
        $this->mailer->isHTML($withHtml);
        $this->mailer->Host = SMTP_HOST;
        $this->mailer->Port = SMTP_PORT;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SMTP_USERNAME;
        $this->mailer->Password = SMTP_PASSORD;

        
        
       }
        
    }
    
    public function update() {
        date_default_timezone_set('Etc/UTC');
        $okay=false;
       if($this->mailer !=null)
       {
           
          $this->mailer->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
           
           
         $this->mailer->setFrom($this->from,"The Event");
         $this->mailer->Subject=$this->subject;
         $this->mailer->addReplyTo($this->from);  
         $this->mailer->addAddress($this->to,$this->name);
         $this->mailer->msgHTML($this->message);    
         
         if($this->mailer->send())
         {
          $okay=true;
          
         }else{
             //Problem with the SMTP configuration SSL will retify it soon
             echo $this->mailer->ErrorInfo;
         }
      }
       return $okay;
        
    }

//put your code here
}
