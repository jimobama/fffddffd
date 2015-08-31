<?php

/*
The file update the user information 
 */

/**
 * Description of UpdateUserController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryUpdateUser.php");
include_once("Models/TryFetchUser.php");

class UpdateUserController  implements IController{
    
    
    
    public function Index() {
        
        
        $email= HttpResquestHandler::getParam("email");       
        $password= HttpResquestHandler::getParam("password");
        $fullname= HttpResquestHandler::getParam("fullname");
        $long= (HttpResquestHandler::getParam("long")!=null)?HttpResquestHandler::getParam("long"):0.000;
        $lat= (HttpResquestHandler::getParam("lat")!=null)?HttpResquestHandler::getParam("long"):0.000; 
        
        return $this->Update($email,$password,$fullname, $long,$lat);
        
    }
    
    
    public function Update($email,$password,$fullname, $long,$lat)
    {
        $jsonView = new JsonViewer();
        
        $response=array();
        $response["success"]=0;
       
        
        $tryLogin = new TryUserLogin($email,$password);
        //if exists 
        if($tryLogin->isExists())
        {
            //fetch user 
            
             $tryFectUser= new TryFetchUser();
             $user=  $tryFectUser->fetch($email);
        if($user != null){ 
                 $user = json_encode($user);
                 $user_php= json_decode($user);
                 //update the user information
                 
                  $tryUpdate= new TryUpdateUser($user_php->ID,$fullname, $long,$lat);
                  $returnVal= $tryUpdate->update();
                 
                  if($returnVal)
                  {
                       $response["success"]=1;
                       $response["message"] =  "user information updated";
                  }else{
                       $response["error_message"] =  "Could not update records contact administrator";
                  }
                
          } else{
               $response["message"] =  "user information could not be update";
          }
             
              
            
        }else{
             $response["error_message"]="Valid email or password information passed for update";
        }
       $jsonView->setContent($response);
                
        return  $jsonView;
                
    }

}
