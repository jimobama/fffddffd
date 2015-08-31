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
             $db->createField(EventTable::Id, "Varchar(40)", "PRIMARY KEY");
             
             $db->createField(EventTable::Privacy, "Varchar(50)", "Not Null");
              $db->createField(EventTable::Status, "int", "DEFAULT 0");
             $db->createField(EventTable::CreatorId, "Varchar(40)", "NOT NULL");
             $db->createField(EventTable::Title, "Varchar(50)", "NOT NULL");
             $db->createField(EventTable::Venue, "Varchar(40)", " Not null");
             $db->createField(EventTable::CreateDate, "Varchar(40)", "NOT NULL");
             $db->createField(EventTable::Description, "Text", "");
             $db->createField(EventTable::StartDate, " Varchar(40)", "NOT NULL");  
             $db->createField(EventTable::Going, "int", "default 0");  
             $db->createField(EventTable::SeachableKeywords, "Text", "");  
             $db->createField(EventTable::CurrencyCountry, "Varchar(50)", ""); 
             $db->createField(EventTable::Image, " Varchar(50)", "");   
             $db->createField(EventTable::Fees, "DOUBLE(16,2)", "default 0.0");  
             //$db->createFields(EventTable::StartTime, "Varchar(40)", "NOT NULL");            
             $db->createField(EventTable::Duration, "Varchar(50)", "Not Null");
             
             $db->createField("FULLTEXT KEY ".EventTable::Title, "(".EventTable::Title.","
                      .EventTable::Description.",".EventTable::SeachableKeywords.",".EventTable::Venue.")","");  
            
        
             
             $db->createTable(EventTable::TableName);
            
       
    }

//put your code here
}
