<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryRemoveEvent
 *
 * @author hacks
 */
include_once("Models/services/TryRemove.php");
include_once("Models/EventTable.php");


class TryRemoveEvent implements TryRemove {
    

    
    public function remove($id) {
        
        $db= new Database();
       
        
        $sql="UPDATE ".EventTable::TableName." set ".EventTable::Status."=:status WHERE ".EventTable::Id."=:id";        
        $stmt= $db->prepare($sql);
        $stmt->bindValue(":id", addslashes($id));
        $stmt->bindValue(":status",0);
        $status=$stmt->execute();
        if($status){
           return true;
        }
        return false;
        
    }

//put your code here
}
