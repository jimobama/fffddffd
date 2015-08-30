<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchEvent
 *
 * @author hacks
 */
include_once("Models/TryFetch.php");
include_once("Models/EventTable.php");

class TryFetchUserEvent implements TryFetch{
    
    private $noItemsToFetch;
    
     public function __construct($startLimited=0) {
        $this->noItemsToFetch= intval($startLimited);
     } 
    public function fetch($id) {
        
       $db= new Database();
       $eventTable= new  EventTable();
       $eventTable->Create($db);
        $sql="";
       if( $this->noItemsToFetch>0){
            $sql="select *from ".EventTable::TableName 
                 ." where ".EventTable::CreatorId." =:id  AND ".EventTable::Status." > :zero ORDER BY "
                 .EventTable::StartDate. " ASC LIMIT $this->noItemsToFetch";
       }else{
          $sql="select *from ".EventTable::TableName 
                 ." where ".EventTable::CreatorId." =:id ORDER BY "
                 .EventTable::StartDate. " ASC";
       }
         
        $stmt= $db->prepare($sql);
        $stmt->bindValue(":zero", 0);
        $stmt->bindValue(":id", $id);
        $status= $stmt->execute();
        
        if($status)
        {
            $rows=$stmt->fetchAll(Database::FETCH_ASSOC);
            for($i=0; $i < count($rows);$i++)
            {
                $rows[$i][EventTable::Image]=IMAGE_EVENT_PATHS.$rows[$i][EventTable::Image];
            }
              return $rows; 
       }else{
            print_r($stmt->errorInfo());
        }
       
        
    }
    
    //Return the total number of events in database
   public static function count()
    {
        $count=0;
        $database = new Database();
        if($database!=null)
        {
            
            $query="select *from ".EventTable::TableName;
            $stmt= $database->prepare($query);
            $status= $stmt->execute();
            if($status){
                
                $count= $stmt->rowCount();
            }
            
        }
        
        return $count;
         
    }

    public static function FetchById($eventId)
    {
         $event=null;
        $database = new Database();
        if($database!=null)
        {
            $query="select *from ".EventTable::TableName." WHERE ".EventTable::Id." = :id";
            $stmt= $database->prepare($query);
            $stmt->bindValue(":id",  addslashes($eventId));
            $status= $stmt->execute();
            if($status && $stmt->rowCount()>0){                   
                $event= $stmt->fetch(Database::FETCH_ASSOC);
                $event[EventTable::Image]=IMAGE_EVENT_PATHS. $event[EventTable::Image];
                
            }
            
        }
        
        return $event;
        
    }
//put your code here
}
