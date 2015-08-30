<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateUser
 *
 * @author hacks
 */
include_once("Models/TryUpdate.php");


class TryUpdateUser implements TryUpdate {
    
    private $id;
    private $fullname;   
    private $long;
    private $lat;
    private $db;
    
    
    function __construct($id,$fullname, $long, $lat) {
        
       
        $this->id=$id;
        $this->fullname=$fullname;
        $this->long=$long;
        $this->lat=$lat;      
        $this->db= new Database();
        $this->db->Create(new UserTable());
    }
    
    
    public function update() {
        
        $return =false;
        if($this->validated() &&  $this->db !=null)
        {
           
                   
            
       $query="Update ".UserTable::TableName." set ".UserTable::FullName."=:name, "
               .UserTable::GeoLat."=:lat_r, "
               .UserTable::GeoLong."=:long_r "
               . " where  ".
                UserTable::ID."=:id";
        
        $stmt= $this->db->prepare($query);
       
        $stmt->bindValue(":id", $this->id);
         $stmt->bindValue(":lat_r", $this->lat);
        $stmt->bindValue(":long_r", $this->long);
        $stmt->bindValue(":name",$this->fullname);
        $status=$stmt->execute();
        
        if($status)
        {            
            $return =true; 
        }
        
        }
        
        return $return;        
    }
    
    private function validated()
    {
        $okay=false;
        
        if($this->fullname!=null){
            $okay=true;
        }
        
        
        return $okay;
        
    }

//put your code here
}
