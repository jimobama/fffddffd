<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventTable
 *
 * @author hacks
 */
require_once("Models/ITable.php");
class EventTable implements ITable {
     
    
    const TableName="tbl_event_devents";
    const Id="Id";
    const CreatorId="CreatorId";
    const Title="Title";
    const CreateDate="CreateDate";
    const Description="Description";
    const StartDate="StartDate";
    const Duration="Duration";  
    const Venue ="Venue";
    const Privacy="privacy";
    const Status="Status";
    const Image="img_src";
    const Going="going";
    const SeachableKeywords="search_keywords";
    const CurrencyCountry="current_country";
    const Fees="fees";
    
    public function Create(\Database $db)
    {
        
        if($db ==null){
            $db= new Database();
        }
        
            //create the table
             $db->createFields(EventTable::Id, "Varchar(40)", "PRIMARY KEY");
             
             $db->createFields(EventTable::Privacy, "Varchar(50)", "Not Null");
              $db->createFields(EventTable::Status, "int", "DEFAULT 0");
             $db->createFields(EventTable::CreatorId, "Varchar(40)", "NOT NULL");
             $db->createFields(EventTable::Title, "Varchar(50)", "NOT NULL");
             $db->createFields(EventTable::Venue, "Varchar(40)", " Not null");
             $db->createFields(EventTable::CreateDate, "Varchar(40)", "NOT NULL");
             $db->createFields(EventTable::Description, "Text", "");
             $db->createFields(EventTable::StartDate, " Varchar(40)", "NOT NULL");  
             $db->createFields(EventTable::Going, "int", "default 0");  
             $db->createFields(EventTable::SeachableKeywords, "Text", "");  
             $db->createFields(EventTable::CurrencyCountry, "Varchar(50)", ""); 
             $db->createFields(EventTable::Image, " Varchar(50)", "");   
             $db->createFields(EventTable::Fees, "DOUBLE(16,2)", "default 0.0");  
             //$db->createFields(EventTable::StartTime, "Varchar(40)", "NOT NULL");            
             $db->createFields(EventTable::Duration, "Varchar(50)", "Not Null");
             
             $db->createFields("FULLTEXT KEY ".EventTable::Title, "(".EventTable::Title.","
                      .EventTable::Description.",".EventTable::SeachableKeywords.",".EventTable::Venue.")","");  
            
        
             
             $db->createTable(EventTable::TableName);
            
       
    }

//put your code here
}
