<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryChangePasswordController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TrySendEmail.php");
include_once("Models/UserTable.php");
include_once("Models/TryChangePassword.php");




class TryChangePasswordController implements IController {
    
    
    public function Index() {
        
        $email = HttpResquestHandler::getParam("email");
        $resetCode= HttpResquestHandler::getParam("resetCode");
        $newpassword =  HttpResquestHandler::getParam("password");        
        
        return $this->tryChangePassword($email,$newpassword,$resetCode);
       
        
        
    }
    
    
    private function tryChangePassword($email,$newPassword,$resetCode)
    {
       
       $response=array();
       $response["success"]=0;
      
            
           $tryChange= new TryChangePassword($email,$resetCode, $newPassword);
           $changed=   $tryChange->update();
        
          if($changed)
             {
               $response["success"]=1;
               $response["message"]="password as be changed, new password sent to you";
               $message="You password as be successfully changed"
                    . "\n"
                    . "Username = $email\n"
                    . "New Password = $newPassword\n"
                    . ""
                    . "\n"
                    . "Change Date = ".date("jS /M, Y",Validator::Now());
            
               $from="administrator@devent.support.com";         
            
               $user= $this->getUser($email);
            //send the email if the user exists which suppose to
              if($user !=null ){
                    $subject=" Success changed password";
                    $trySend = new TrySendEmail($message,$from,$user->Email,$user->Fullname,$subject);
                    $trySend->update();
               }
            
           }else{
               $response["error_message"]="invalid authentication details\n" ;
           }
            
        
        
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);
        return  $jsonView;
        
    }


    
    private function getUser($email)
    {
        
        $tryfetch = new TryFetchUser(new UserTable());
        $user=  $tryfetch->fetch($email);
        if($user!=null)
        {
            $user= json_encode($user);
            $user= json_decode($user);
          
        }
        return $user;
    }

//put your code here
}
