<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryGetEventByIdController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchUserEvent.php");
class TryGetEventByIdController implements IController{
    
    
    public function Index() {
        
         $eventId= HttpResquestHandler::RequestParams("event_id");
         $response=array();
         $response["success"]=0;
         
         
         $event= TryFetchUserEvent::FetchById($eventId);
         if($event!=null)
         {
             $response["success"]=1;
             $response["event"]= $event;
         }else{
             $response["error_message"]="No event found";//should not happen
         }
         
        $json= new JsonViewer();
        $json->setContent($response);
        return $json;
    }

//put your code here
}
