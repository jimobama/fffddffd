<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateEventDescriptionController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUserLogin.php");
include_once("Models/EventTable.php");


class TryUpdateEventDescriptionController implements IController {
    public function Index() {
         $desc= HttpResquestHandler::RequestParams("desc");
         $event_id= HttpResquestHandler::RequestParams("event_id");
         $user_id= HttpResquestHandler::RequestParams("user_id");
         $user_password= HttpResquestHandler::RequestParams("password");
         return $this->updateEvent($user_id,$user_password,$event_id,$desc);
    }
    
    
   
    private function updateEvent($user_id,$user_password,$event_id,$desc)
    {
        $response = array();
        $response["success"]=0;
        $jsonView = new JsonViewer();
        $tryLogin = new TryUserLogin($user_id,$user_password);
        if($tryLogin->isExists())
        {
            //update the events
            $database = new Database();
            
            $sql="UPDATE ".EventTable::TableName." set ".EventTable::Description."=:desc WHERE ".EventTable::Id."=:id";
            $smt= $database->prepare($sql);
            $smt->bindValue(":id", $event_id);
            $smt->bindValue(":desc", $desc);
            $status= $smt->execute();
            if($status){
                $response["success"]=1;
                $response["message"]="Description updated";
            }else{
               $response["error_message"]="No event with such information found"; 
            }
            
        }else{
             $response["error_message"]="Invalid user login details";
        }
        
        $jsonView->setContent($response);
        return $jsonView;
    }
    

//put your code here
}
