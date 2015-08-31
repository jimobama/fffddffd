<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateSearchableKeyword
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUserLogin.php");
include_once("Models/EventTable.php");
class TryUpdateSearchableKeywordController implements IController{
    public function Index() {
        
         $searchablekeywords= HttpResquestHandler::getParam("keywords");
         $event_id= HttpResquestHandler::getParam("event_id");
         $user_id= HttpResquestHandler::getParam("user_id");
         $user_password= HttpResquestHandler::getParam("password");
         return $this->updateEvent($user_id,$user_password,$event_id,$searchablekeywords);
        
    }

//put your code here
    private function updateEvent($user_id,$user_password,$event_id,$searchablekeywords)
    {
        $response = array();
        $response["success"]=0;
        $jsonView = new JsonViewer();
        $tryLogin = new TryUserLogin($user_id,$user_password);
        if($tryLogin->isExists())
        {
            //update the events
            $database = new Database();
            
            $sql="UPDATE ".EventTable::TableName." set ".EventTable::SeachableKeywords."=:search_keys WHERE ".EventTable::Id."=:id";
            $smt= $database->prepare($sql);
            $smt->bindValue(":id", $event_id);
            $smt->bindValue(":search_keys", $searchablekeywords);
            $status= $smt->execute();
            if($status){
                $response["success"]=1;
                $response["message"]="update searchable keys";
            }else{
               $response["error_message"]="Invalid event details provided"; 
            }
            
        }else{
             $response["error_message"]="Invalid login details";
        }
        
        $jsonView->setContent($response);
        return $jsonView;
    }
}
