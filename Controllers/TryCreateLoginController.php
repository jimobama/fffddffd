<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryVerifyAccountController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/entities/users/User.php");
include_once("Models/user_classes/TryFetchUser.php");
include_once("Models/user_classes/TryCreateLoginDetail.php");


class TryCreateLoginController implements IController{
    
    
    
    public function Index() {
        
        $email =  HttpResquestHandler::getParam("email");
        $vcode =  HttpResquestHandler::getParam("vcode");
        $newpassword =  HttpResquestHandler::getParam("password");
        
        
        return $this->createLoginDetailsFrom( $email, $vcode, $newpassword);
        
    }
    
    
    
    private function createLoginDetailsFrom( $email, $vcode, $newpassword)
    {
        $response=array();
        $response["success"]=0;
        
         
        $tryFetchUser= new TryFetchUser();
        $dbuser = $tryFetchUser->fetch($email);
        if($dbuser !=null){
           $php_user = new User(json_encode($dbuser));
           
           if(trim($php_user->getVerificationCode())==trim($vcode)){
               //create the new user login details 
               
               //check if the user login details account already exists or created 
               
         
               
             $tryCreateLogin= new   TryCreateLoginDetail($php_user->getId(),$newpassword);
            
             if(!$tryCreateLogin->isExists())
                 {
                 
                if($this->validate($newpassword)){
                    if($tryCreateLogin->create())
                    {

                       $response["success"]=1;  
                    }else{
                        //This should never happen 
                       $response["error_message"]  ="Error occur during login details creations";    
                    }
                }else{
                    $response["error_message"]  ="Password characters must be more than 3";  
                }
                    
                 }else{                
                  $response["error_message"]  ="This account has already be verified";      
                 }
                 
               
           }else{
              $response["error_message"]  ="Invalid verification code provided"; 
           }
           
           
           
           
        }else{
           $response["error_message"]  ="No account found with the given details";
        }
         
         
         
         $jsonView = new JsonViewer();
         
         $jsonView->setContent($response);
         
         return $jsonView;
        
    }
    
    
    private function validate($password){
        
        if($password=="" || strlen($password)<=3){
            return false;
        }
        return true;
    }

//put your code here
}
