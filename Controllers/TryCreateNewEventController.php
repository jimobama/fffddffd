<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryCreateNewEventController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TryUserLogin.php");
include_once("Models/Event.php");
include_once("Models/TryCreateEvent.php");
include_once("Includes/TryParseDate.php");



class TryCreateNewEventController implements IController {
    
    private $__message="";
    
    public function Index() {
        
         
        $email=  HttpResquestHandler::RequestParams("email");
        $password=  HttpResquestHandler::RequestParams("password");
        $title=  HttpResquestHandler::RequestParams("title");
        $venue= HttpResquestHandler::RequestParams("venue");      
     //   $dateOfEvent=  HttpResquestHandler::RequestParams("dateOfEvent");
        $privacy =  HttpResquestHandler::RequestParams("privacy");
       // $duration = HttpResquestHandler::RequestParams("duration");      
        $fees = HttpResquestHandler::RequestParams("fees");
        //$searchable = HttpResquestHandler::RequestParams("searchable_keywords");
        $currency_country = HttpResquestHandler::RequestParams("currency_country");      
        
      //  $eventdata= floatval($dateOfEvent)/1000;
        
        return $this->createNewEvent($email,$password,$title,$venue,$privacy,$fees,$currency_country);
        
        
    }

    
    
    private function createNewEvent($email,$password,$title,$venue,$privacy,$fees,$currency_country="")
    {
        $response = array();
        $response["success"]=0;
       //verify if the user exists
  
        $trycheck=new TryUserLogin($email, $password);
        if($trycheck->isExists())
        {
            $tryfetch= new TryFetchUser();

            $user= $tryfetch->fetch($email);

            if($user !=null){
              

                $user_json= json_encode($user);
                $user_php= json_decode($user_json);
            
              
                $event= new Event($user_php->ID,$title,$venue,$privacy);
                $event->setCreateDate(Validator::Now());
                $event->setDuration("");
                $event->setStartDate("");   
                $event->setDescription("");
                $event->setFees($fees);
                $event->setCurrencyCountry($currency_country);
               // $event->setSearchableKeywords($searchable);
             
                //validate the events
                if($this->validate($event)){ 
                     $response = $this->commit($event);
                }else{
                   $response["error_message"]=$this->__message;  
                }
            }
           
            
        }
        else{
               $response["success"]=0;
               $response["error_message"]="relogin into your account , user session expired";
            }
            
            
            
        $jsonView= new JsonViewer();
        $jsonView->setContent($response);
        
        
        return  $jsonView;
         
    }
    
private function validate(Event $event)
    {
     
   
        if(Validator::IsEmpty(trim($event->getTitle())))
        {           
            $this->__message="Specific the title of the event!";
        }else if(Validator::IsEmpty($event->getVenue()))
        {   $this->__message="Enter the event venue please!";
        }
        
        else{
            return true;
        }
        
        return false;
    }
    
 private function commit($event){
     
    
      $response["success"]=0;
      $tryCreateEvent= new TryCreateEvent($event);
              
               $status= $tryCreateEvent->Create();  
               if($status){
               $response["success"]=1;
               $response["message"]="created";
               }else{
                    $response["error_message"]="could not create event contact administrator";
               }
         return $response;
 }   

//put your code here
}
