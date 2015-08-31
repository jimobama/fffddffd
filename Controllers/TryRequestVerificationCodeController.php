<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResetPasswordController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/user_classes/TryFetchUser.php");
include_once("appdata/tables/UserTable.php");
include_once("Models/user_classes/TryResetPassword.php");
include_once("Includes/TrySendEmail.php");
include_once("Models/entities/users/User.php");

class TryRequestVerificationCodeController implements IController{
    
    
    public function Index() {
        
       $jsonView = new JsonViewer();
       $response =array();
       $response["success"]=0;
       
         $email =  HttpResquestHandler::getParam("email");
     
          $tryReset= new  TryResetPassword($email);
          $code= $tryReset->update();
          if($code !=null){
              
              $response["success"]=1;
              $tryFetchUser= new TryFetchUser();
              $user= $tryFetchUser->fetch($email);  
            
              $phpuser=new User(json_encode($user));              
              $status= $this->sendUserResetCode($phpuser);
              if($status){
                $response["message"]="The verification as be sent to the email address $email";    
              }
             
          }else{
               //this should not happen
              die("Debugging  Error in update reset code");
          }
       
       
       
        $jsonView->setContent($response);
        return $jsonView;
        
    }
    
    
    private function sendUserResetCode(User $user){
        
           
              
              $fullname=$user->getFullname();
              $email =$user->getEmail();
              $code= $user->getVerificationCode();
              
              $message="Dear   $fullname, <br> "
                      . "\t The account verification code requested by you as be sent , please if this is you continue with the process. "
                      . "<br>Account email : $email<br><br> "
                      . "<br>Reset code : $code<br><br>"
                      . "Copy and paste the reset code on the field provided in your application screen ";
              // send the code to the user throw email or text message
              $mailer = new TrySendEmail($message,ADMINISTRATOR_EMAIL,$email,$fullname);             
             return  $mailer->update();
    }

//put your code here
}
