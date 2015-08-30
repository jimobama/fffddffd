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
include_once("Models/TryFetchUser.php");
include_once("Models/UserTable.php");
include_once("Models/TryResetPassword.php");
include_once("Models/TrySendEmail.php");

class ResetPasswordController implements IController{
    
    
    public function Index() {
        
       $jsonView = new JsonViewer();
       $response =array();
       $response["success"]=0;
       
       $email =  HttpResquestHandler::RequestParams("email");
        
       $tryFetchUser= new TryFetchUser(new UserTable());
       $user= $tryFetchUser->fetch($email);
       
       if($user !=null)
       {
          $tryReset= new  TryResetPassword($email);
          $code= $tryReset->update();
          if($code !=null){
              $user= json_encode($user);
              $response = $this->sendUserResetCode($user, $code);
             
          }else{
                $user= json_encode($user);
                $user= json_decode($user);
                $response["error_message"]="Oops $user->Fullname, The side is a bit down";
          }
          
       }else{
           $response["error_message"]="No user found with the give email address";
       }
       
       
        $jsonView->setContent($response);
        return $jsonView;
        
        
    }
    
    
    private function sendUserResetCode($jsonObject, $code){
        
              $response=array();
              
              $user_php = json_decode($jsonObject);
              
              $message="Dear  $user_php->Fullname, <br> "
                      . "\t Did you request for a verification reset code for your account at DEvent if so this is the verification details "
                      . "<br>Account email : $user_php->Email<br><br> "
                      . "<br>Reset code : $code<br><br>"
                      . "You should copy the reset code and paste on the field at your device screen of the DEvent Page to reset your password. ";
              // send the code to the user throw email or text message
              $mailer = new TrySendEmail($message,ADMINISTRATOR_EMAIL,$user_php->Email,$user_php->Fullname);
              $response["success"]=1;
              if($mailer->update())
              {
                   $response["success"]=1;
                   $response["message"]="A verification code has be sent to this email account [$user_php->Email]";
              }
                $response["message"]="The mail will be sent to you shortly with request code";
              
        return $response;
        
    }

//put your code here
}
