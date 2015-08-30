<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchUserByEventId
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchUserEvent.php");
include_once("Models/TryFetchUser.php");

class TryFetchUserByEventIdController implements IController  {
    
    
    public function Index() {
        
        $event_id= HttpResquestHandler::RequestParams("event_id");
        
        return $this->getUserByEventId($event_id);
        
    }
    
    private function getUserByEventId($event_id)
    {
        $response = array();
        $response["success"]=0;
        
        if($event_id!=null){
       
        $event= TryFetchUserEvent::FetchById($event_id);
        if($event!=null)
        {
           
            //there is an event found
            $json_event=  json_encode($event);
            
            $php_event= json_decode($json_event);
           
            
          $user_id=   $php_event->CreatorId;
           
          if($user_id !=null)
          {
               $tryfetchUser= new TryFetchUser();
               $user=  $tryfetchUser->fetch($user_id);
            
               if($user !=null)
               {
                   $response["success"]=1;
                   $response["user"]=$user;
               }
                
          }
            
            
        }else{
                   $response["error_message"]="No event found ";
        }
        }else{
           $response["error_message"]="Event id must be provided";  
        }
                
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);        
        return $jsonView;
        
    }

//put your code here
}
