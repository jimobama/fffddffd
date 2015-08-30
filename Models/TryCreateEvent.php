<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryCreateEvent
 *
 * @author hacks
 */

require_once("Models/TryCreate.php");
require_once("Models/Event.php");
require_once("Models/EventTable.php");
require_once ("Models/TryFetchUserEvent.php");

class TryCreateEvent implements TryCreate{
    
    const IMAGE_PATH_EVENTS="uploads/events/images/";
    private $event=Null;
    private $db;
    function __construct (Event $event)
    {        
        $this->db= new Database();
        $this->event =$event;
       
       
       
    }
    
    public function Create() {
        
        $okay=false;
        $statusUpload=false;
        if($this->event!=null){  
            $filename="default.png" ;
            $this->db->Create( new EventTable());
            
            //create new events here
            $sql= "Insert into ".EventTable::TableName.
                    "(".EventTable::Image.","
                    .EventTable::Id.","
                    .EventTable::CreatorId.","                    
                    .EventTable::CreateDate.","
                    .EventTable::Description.","
                    .EventTable::Duration.","
                    .EventTable::StartDate.",".
                    EventTable::Fees.",".
                    EventTable::SeachableKeywords.",".
                    EventTable::CurrencyCountry.",".
                    EventTable::Status.",".
                    EventTable::Title.",".
                    EventTable::Privacy.",".
                    EventTable::Venue.")Values(:image,:Id,:CreatorId,"
                    . ":CreateDate,:Description,:Duration,:StartDate,:Fees,:searchable_keywords,:current_country,:Status,:Title,:Type,:Venue)";
            $stmt= $this->db->prepare($sql);
            
            $stmt->bindValue(":Id",$this->event->getId());
            $stmt->bindValue(":CreatorId", $this->event->getCreatorId());              
            $stmt->bindValue("CreateDate", $this->event->getCreateDate());
            $stmt->bindValue(":Description", "");
            $stmt->bindValue(":Duration", $this->event->getDuration());
            $stmt->bindValue(":StartDate", $this->event->getStartDate());
            $stmt->bindValue(":image", $filename);
            $stmt->bindValue(":Fees", $this->event->getFees());
            $stmt->bindValue(":searchable_keywords", "");
            $stmt->bindValue(":current_country", $this->event->getCurrencyCountry());
            $stmt->bindValue(":Status", (strtolower(trim($this->event->getType()))=="private")?0:1);
            $stmt->bindValue(":Title", $this->event->getTitle());
            $stmt->bindValue(":Type", $this->event->getType());
            $stmt->bindValue(":Venue", $this->event->getVenue());
            
           if($stmt->execute()){
               
           if($stmt->rowCount()>0) {
                    $okay=true;
                }else{
                    //echo "Could not be created dont know why";
                }
                
            }
            
        }
        return $okay;
        
    }
    
    
    
    
   

//put your code here
}
