<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrySearchEventWithQueryStrings
 *
 * @author hacks
 */
include_once("Models/TryFetch.php");
include_once("Models/EventTable.php");

class TrySearchEventWithQueryStrings implements TryFetch{
    
  private $long;
  private $lat;
  private $distance;
  private $events;
 public function __construct()
            {
            
             $events= new ArrayObject();
     
            }
public function fetch($query) {
        
    
            return $this->startQuery($query);
    }
    
    
    
    private function  startQuery($search_string)
    {
        
         
          
            $database = new Database();
            
            $database->Create(new EventTable());
            /*
            
           $sqlFullText= "CREATE FULLTEXT INDEX If Not Exists search ON ".EventTable::TableName.
                   "(".EventTable::Title.",".
                    EventTable::Description.",".
                    EventTable::SeachableKeywords.",".
                    EventTable::Venue.")";
            
             * ".EventTable::Title, "(".EventTable::Title.","
                      .EventTable::Description.",".EventTable::SeachableKeywords.",".EventTable::Venue."
             * 
            $database->runCommand($sqlFullText);
             * */
        
            
            $query_string=$database->quote($search_string);
           
            $squery= "select *from ".EventTable::TableName. " WHERE ( MATCH (".EventTable::Title.",".
                    EventTable::Description.",".
                    EventTable::SeachableKeywords.",". 
                    EventTable::Venue.") AGAINST ($query_string IN BOOLEAN MODE)) AND ".EventTable::Status." > :zero";            
                      
           $stmt=$database->prepare($squery);  
          $stmt->bindValue(":zero", 0);
          $status=  $stmt->execute();
          
          if( $status)
          {
              $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
              for($i=0; $i < count($rows);$i++)
                {
                  $rows[$i][EventTable::Image]=IMAGE_EVENT_PATHS.$rows[$i][EventTable::Image];
                }
             return $rows; 
          }else{
              print_r($stmt->errorInfo());
              return null;
          }
            
             
    }
    
    
    private function parserQueryStringIntoKeywords($query_string)
    {
        $arrayObject = new ArrayObject(explode(",",trim($query_string)));
        $result = new ArrayObject();
       
        if($arrayObject->count()> 0)
        {
            foreach($arrayObject as $value)
            {
                $array= explode(" ",$value);
                foreach($array as $value2)
                 {
                    $keyword= strtolower(trim($value2));
                    
                    if($this->isKeyword( $keyword)){
                   
                      $result->append($value2);
                    }
                    
                  }
                 }
            }
       
      
        
        return $result;        
        
    }

   
    
    private function isKeyword( $keyword)
    {
        if($keyword !="null" 
                && $keyword!="the" 
                && $keyword !="it"
                && $keyword!="that" 
                && $keyword !="is" )
           {
            return true;
            }
        return false;
    }
//put your code here
}
