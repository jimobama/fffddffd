<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchUserController
 *
 * @author hacks
 */
require_once("Controllers/IController.php");
require_once("Configuration/JsonViewer.php");
require_once ("Configuration/HttpRequestHandler.php");
require_once("Models/TryUserLogin.php");
require_once("Models/UserTable.php");
require_once("Models/TryFetchUser.php");


class TryFetchUserController implements IController{
    public function Index() {
       $getEmail =  HttpResquestHandler::RequestParams("email") ;
       $getPassword =  HttpResquestHandler::RequestParams("password");  
      
       return $this->getUserInfo($getEmail,$getPassword);
        
    }
    
    
   public function getUserInfo($username, $password)
      {
           $jsonViewer = new    JsonViewer();
          
            $response=array();
            $response["success"]=0;
             
            $userlogin = new   TryUserLogin($username, $password); 
            
            if( $userlogin->isExists())
               {
                    $tryUser= new  TryFetchUser(new UserTable());
                    $user =$tryUser->fetch($username);                   
                    $response["success"]=1;
                    $response["user"]=$user;

               }else{
                   //check if the user email exist 
                     $error="Invalid username[$username] or password";
                   
                    $response["message_error"]=$error;
               }
       
       //set the view content to return to the users phone
       
       $jsonViewer->setContent($response) ;
           
          
       return  $jsonViewer; 
      }

//put your code here
}
