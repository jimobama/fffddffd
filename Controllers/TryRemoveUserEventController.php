<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryRemoveUserEventController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TryRemoveEvent.php");


class TryRemoveUserEventController  implements IController{
    
    
    public function Index() {
        
        
          $user_id= HttpResquestHandler::RequestParams("user_id");        
          $password= HttpResquestHandler::RequestParams("password");
          $event_id= HttpResquestHandler::RequestParams("event_id");         
        
          return $this->removeEvent($user_id,$password,$event_id);
    }

    
    private function removeEvent($user_id,$password,$event_id){
        
        $response=array();
        $response["success"]=0;
       
        $tryUser =new TryFetchUser();
        $user=  $tryUser->fetch($user_id);
        if($user!=null){
            //convert to php object
            $json_user= json_encode($user);
            $php_user= json_decode($json_user);
            
            $email = $php_user->Email;
            $trylogin = new TryUserLogin($email,$password);
            if($trylogin->isExists())
                {
                //now check if the event                
                $tryRemove = new TryRemoveEvent();
                if( $tryRemove->remove($event_id))
                {
                      $response["success"]=1;
                      $response["message"]="Event successfully removed";
                }else{
                    $response["error_message"]="Invalid event details provided $event_id "; 
                }
                
            }else{
                   $response["error_message"]="No such information found with the login information";
            }
            
        }else{
             $response["error_message"]="Invalid user login information provided";
        }
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);
        return $jsonView;
    }
//put your code here
}
