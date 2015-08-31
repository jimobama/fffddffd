<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateEventController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchUserEvent.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryUpdateUserEvent.php");
include_once("Models/Event.php");

class TryUpdateEventController implements IController {
    
    private $__message="";
    public function Index() {
        
        $eventid = HttpResquestHandler::getParam("event_id");             
        $email=  HttpResquestHandler::getParam("email");
        $password=  HttpResquestHandler::getParam("password");
        $title=  HttpResquestHandler::getParam("title");
        $venue= HttpResquestHandler::getParam("venue"); 
        $privacy =  HttpResquestHandler::getParam("privacy");      
     
        
        return $this->update($eventid,$title, $venue,$privacy,$email,$password);
        
    }

    //The function will update the user events
    
private function update($eventid,$title, $venue,$privacy,$email,$password)
    {
    $response =array();
     $response["success"]=0;
    
        //check if the user is valid
    $tryLoginUser= new TryUserLogin($email,$password);
    if($tryLoginUser->isExists())
        {
          //get the current event if exist
        $event = TryFetchUserEvent::FetchById($eventid);
        if($event!=null){
            
            //set the event properties to the new added informations
            $eventObject= $this->parserEventJson(json_encode($event));
            if($eventObject!=null){
                
                $eventObject->setTitle($title);
                $eventObject->setId($eventid);
                $eventObject->setVenue($venue);                
                $eventObject->setType($privacy);               
                
                //validate to match such the date send is current
                if($this->validateEvent($eventObject))
                {
                    //update the vent
                    $tryUpdateEvent = new TryUpdateUserEvent($eventObject);
                    $status= $tryUpdateEvent->update();
                    if($status){
                         $response["success"]=1;
                         $response["message"]="Event Updated";
                        
                    }else{
                        $response["message"]="There was error when trying to update event";
                    }
                }else{
                   $response["error_message"]=$this->__message; 
                }             
            }
            
        }else{
            $response["error_message"]="Know event with the given information"; 
        }        
          //exist

        }else{
             $response["error_message"]="Unknown user request...";
        }
    
        
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);
        return  $jsonView;
    }
    
    //The function will parser the event into php event object
    
    private function parserEventJson($jsonevent)
    {
        $event=null;
        //convert to php object
        $php_object = json_decode($jsonevent);     
        if($php_object!=null){
                    
         $event= new Event($php_object->CreatorId, $php_object->Title,$php_object->Venue,$php_object->privacy);
         $event->setCurrencyCountry($php_object->current_country);
         $event->setSearchableKeywords($php_object->search_keywords);
         $event->setFees($php_object->fees);
         $event->setCurrencyCountry( $php_object->current_country);
         $event->setCurrencyCountry( $php_object->current_country);
         $event->setDescription($php_object->Description);
         $event->setStartDate($php_object->StartDate);
         $event->setGoing($php_object->going);   
          
        }
        
        return $event;        
    }
      
    
    private function validateEvent(Event $event)
    {
        $okay =false;
        
        if(trim($event->getTitle())=="")
        {
            $this->__message="Enter a valid title !!!";
            
        }else if(trim($event->getType())==""){
            $this->__message="Select a event privacy type either public or private!!!"; 
        }else if(trim($event->getVenue())==""){
             $this->__message="Enter an address for the event";             
        }else{            
            
            $okay=true;
        }
       
       
       return $okay;
       
        
    }
//put your code here
}
