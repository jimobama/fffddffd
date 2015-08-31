<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrySearchEventBaseOnLocationController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TrySearchEventWithQueryStrings.php");


class TryQueryEventController  implements IController {
    
    
    public function Index() {
        
        $query=  HttpResquestHandler::getParam("query_string");
        
        return $this->fetchEventOn($query);
        
        
    }
    
    private function fetchEventOn($query)
    {
        $json = new JsonViewer();
         $response = array();
         $response["success"]=0;
        
         $trySearchEvent = new TrySearchEventWithQueryStrings();
      
         $events=   $trySearchEvent->fetch($query);
        
         if($events !=null ){
              $response["success"]=1;
              $response["events"]=$events;             
         }else{
               $response["error_message"]="No search found with $query ";        
         }
      
        $json->setContent($response); 
        return $json;
        
        
    }

//put your code here
}
