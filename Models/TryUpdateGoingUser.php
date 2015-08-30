<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateGoingUser
 *
 * @author hacks
 */
include_once("Models/TryUpdate.php");
include_once("Models/TableAttendance.php");
include_once("Models/EventTable.php");
class TryUpdateGoingUser implements  TryUpdate {
    
    private $user_id;
    private $event_id;
   public function __construct($user_id,$event_id)
    {
      $this->event_id=$event_id;
      $this->user_id=$user_id;
    }
    
    public function update() {
        
        $okay=false;
        $database= new Database();
        $database->Create(new TableAttendance());
        if(!$this->isExists())
        {
           
            $query="INSERT INTO ".TableAttendance::TableName.
                    " (".TableAttendance::EVENT_ID.",".TableAttendance::USER_ID.
                    ")VALUES(:event_id,:user_id)";
            
            
            $stmt= $database->prepare($query);
            $stmt->bindValue(":event_id", $this->event_id);
            $stmt->bindValue(":user_id", $this->user_id);
            $result=  $stmt->execute();
            if($result && $stmt->rowCount()>0){
            
                //update the number of user attended
                if($this->updateEventRecord())
                {
                    $okay= true;
                    
                }else{
                    $this->deletePreviousGoingUser();
                   
                }
            }else{
           
            }
                
            
        }
      
       return  $okay;
    }
    
    private function updateEventRecord($increment=1)
    {  
        
        $query="UPDATE ".EventTable::TableName." SET ".EventTable::Going." = "
                .EventTable::Going." + :going  WHERE ".EventTable::Id."=:event_id AND ".EventTable::Going." >= :zero_value";
        
        $db= new Database();
         $db->Create(new EventTable());
         
        $stmt= $db->prepare($query);
        $stmt->bindValue(":going",$increment);
        $stmt->bindValue(":event_id", $this->event_id);
        $stmt->bindValue(":zero_value", 0);
        
        
        $result =  $stmt->execute();
        if($result && $stmt->rowCount()>0)
        {
            return true;
        }
        
        return false;
         
   }
   
   private function deletePreviousGoingUser(){
       
       $query = "DELETE FROM ".TableAttendance::TableName.
               " WHERE  ".TableAttendance::EVENT_ID." =:event_id AND ".TableAttendance::USER_ID."= :user_id ";
       
       $database= new Database();
       $stmt=$database->prepare($query);
       $stmt->bindValue(":event_id", $this->event_id);
       $stmt->bindValue(":user_id", $this->user_id);
       $result =$stmt->execute();
       if( !$result)
       {
           print_r($stmt->errorInfo());
           return false;
       }
       
       return true;
       
       
       
   }
   
    
    public function isExists(){
        $okay=false;
        
          $query = "SELECT * FROM ".TableAttendance::TableName.
               " WHERE  ".TableAttendance::EVENT_ID." =:event_id AND ".TableAttendance::USER_ID."= :user_id";
       
       $database= new Database();
       $database->Create(new TableAttendance());
       $stmt=$database->prepare($query);
       $stmt->bindValue(":event_id", $this->event_id);
       $stmt->bindValue(":user_id", $this->user_id);
       $result =$stmt->execute();
       if($result)
       {
           if($stmt->rowCount()>0)
           {
                $okay=true;
           }
       }
     
        return $okay;
    }
    
    
    public function remove(){
        
        $okay=false;
        if($this->isExists())
        {
            $status=$this->updateEventRecord(-1)  ;
            if($status)
            {
                //delete the  going user
            $okay=  $this->deletePreviousGoingUser();
                
            }
        }
        
        return $okay;
    }

//put your code here
}
