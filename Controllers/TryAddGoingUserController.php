<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryAddGoingUserController
 *
 * @author hacks
 */

include_once("Controllers/IController.php");
include_once("Models/TryUpdateGoingUser.php");
include_once("Models/TryFetchUserEvent.php");
include_once("Models/TryFetchUser.php");

class TryAddGoingUserController  implements IController{
    
    
    public function Index() {
        
        $user_id = HttpResquestHandler::getParam("user_id");
        $event_id = HttpResquestHandler::getParam("event_id");
        
        return $this->getUpdateEvent($user_id,$event_id);
        
    }

    
    private function getUpdateEvent($user_id,$event_id)
    {
        $response=array();
        $response["success"]=0;
        $jsonView= new JsonViewer();
        
        $events= TryFetchUserEvent::FetchById($event_id);
        if( $events !=null){
            $tryuser =new  TryFetchUser(new UserTable());
            
        $user= $tryuser->fetch($user_id);
        if($user!=null){
           
           $response= $this->addNewAttendance($events,$user_id,$event_id);
        
        }else{
             $response["error_message"]="User no found [$user_id]"; 
        }
        }else{
             $response["error_message"]="No event find with the given event id [$event_id]";  
        }
        
        
        $jsonView->setContent($response);
        return $jsonView;
        
    }
    
    
    
  private function addNewAttendance($events,$user_id,$event_id){
      
      
       $response=array();
       $response["success"]=0;
       $tryUpdate= new TryUpdateGoingUser($user_id,$event_id);
       $event_json=  json_encode($events);
       $event_php= json_decode($event_json);
        
        if(!$tryUpdate->isExists())
        {
            $result= $tryUpdate->update();
            if($result){                
              $response["success"]=1;
               $response["message"]="Request sent";
                
            }else{                
                
                 $response["error_message"]="Wasn't able to add the user to attend the event  $event_php->Title ";  
            }
            
        }  else{    
            $response["success"]=1;
            $response["message"]="Request cancelled";
            $tryUpdate->remove();
        }
        
        return  $response;
  }
    
    
    
    
}
