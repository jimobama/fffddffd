<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateEventDateController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUpdateDateEventModel.php");
include_once("Models/TryUserLogin.php");


class TryUpdateEventDateController implements IController{
    private $__message="";
    
    
    public function Index() {
        
         $user_id= HttpResquestHandler::getParam("user_id");
         $password= HttpResquestHandler::getParam("password");
         $event_id= HttpResquestHandler::getParam("event_id");
         $startDate= HttpResquestHandler::getParam("start");
         $endDate= HttpResquestHandler::getParam("end");  
        
        
        return $this->updateEventDateVenue($user_id,$password,$event_id, $startDate,$endDate);
    }

//put your code here
    
    private function updateEventDateVenue($user_id,$password,$event_id, $startDate,$endDate)
    {
        $response=array();
        $response["success"]=0;
        
        if($this->isValidated($startDate,$endDate))
        {
           
            //dates are not in pass and are in the future
            $try=new TryUserLogin($user_id,$password);
            if($try->isExists()){
                  
                //The account exist
                $tryUpdateDateEvent = new TryUpdateDateEventModel($event_id, $startDate,$endDate);
                $result=$tryUpdateDateEvent->update();
                if($result){
                  $response["success"]=1;  
                  $response["message"]="Event date updated successfully";
                }else{
                  $response["error_message"]="Invalid event request on unexisting event";   
                }
            }else{
                $response["error_message"]="No user found";
            }
        }else{
            $response["error_message"]=$this->__message;
        }
        
        
        
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);
        return $jsonView;       
        
    }
    
    private function isValidated($start,$end){
        $longStart = floatval($start);
        $longEnd = floatval($end);
        if($longStart >=$longEnd){
            $this->__message= "Event end should be in the future and should be ahead of start date.";
        }else if($longStart < time()){
            $this->__message ="Event start should be in the future";
        }else{
            return true;
        }
        
        return false;
    }
}
