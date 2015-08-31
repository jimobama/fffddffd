<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateDateEventModel
 *
 * @author hacks
 */
include_once("Models/TryUpdate.php");
include_once("Models/EventTable.php");

class TryUpdateDateEventModel implements TryUpdate {
    
    private $event_start;
    private $event_end;
    private $event_id;
    
    function __construct($event_id, $eventStart, $eventEnd) {
        $this->event_end=$eventEnd;
        $this->event_start=$eventStart;
        $this->event_id=$event_id;       
      }
    

    public function update() {
        
        
        $db = new Database();
        $sql="UPDATE ".EventTable::TableName." set ".EventTable::StartDate."=:startDate , ".EventTable::Duration."=:endDate "
                . " WHERE ".EventTable::Id." =:id";
         $stmt= $db->prepare($sql);
         $stmt->bindValue(":startDate", floatval($this->event_start));
         $stmt->bindValue(":endDate", floatval($this->event_end));
         $stmt->bindValue(":id",trim($this->event_id));
        // echo $this->event_id." updated";
         $status= $stmt->execute();
         if($status){             
           if($stmt->rowCount()>0){
               return true;
           }
        }
        return false;
//put your code here
}



}

