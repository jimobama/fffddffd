<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchEventController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TryFetchUserEvent.php");

class TryFetchUserEventController implements IController{
    
    
    public function Index() {
        
      
        $email = HttpResquestHandler::getParam("email");
        $password = HttpResquestHandler::getParam("password");  
        $noevents=HttpResquestHandler::getParam("numberofevents");    ;
         
       return $this->getUserEvents($email,$password, $noevents);
      
        
    }
    
    
    private function getUserEvents($email,$password, $noevents)
    {
      
       
        $response=array();
        $response["success"]=0;
        $tryLogin = new TryUserLogin($email,$password);
       if($tryLogin->isExists())
       {
        
        
        $tryfetch = new TryFetchUser(new UserTable());
        $user= $tryfetch->fetch($email);
        if($user !=null)
        { 
            $response["success"]=1;
            $user_json= json_encode($user);
            $php_user= json_decode($user_json);
           
             $tryfetchEvents=new TryFetchUserEvent($noevents);
             $events=  $tryfetchEvents->fetch($php_user->ID);
             $response["events"]=$events;
           
       }else{
            $response["error_message"]="Invalid user request";
       }
   
        
        $json= new JsonViewer();
        $json->setContent($response);
        return $json;
        }
    }
    
  
    
    

//put your code here
}
