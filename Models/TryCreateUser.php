<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreateUser
 *
 * @author hacks
 */
require_once("Models/TryCreate.php");
require_once("Models/UserTable.php");


class TryCreateUser implements TryCreate{
    //put your code here
    private $email;
    private $fullname;
    private $password;
    private $long;
    private $lat;
    private $db;
    function __construct($email,$fullname,$password,$long,$lat) {     
        $this->email=$email;
        $this->fullname=$fullname;
        $this->password=$password;
        $this->long=$long;
        $this->lat=$lat;
        $this->db = new Database();      
    }

    /*Create a  user data records*/
    public function create() {
        
          
        $this->db->Create(new UserTable());
        $okay=false;
       
        $sql= "Insert Into ".UserTable::TableName 
                    ."(".UserTable::FullName.","
                    .UserTable::ID.","
                    .UserTable::UserName.","
                    .UserTable::UserPassword.","
                    .UserTable::GeoLat.","
                    .UserTable::RegisterDate.","
                    .UserTable::Status.","
                    .UserTable::GeoLong.")"                    
                    . " Values"
                    . "(:fullname,:id,:email,:password,:geolat,:registerDate,:status,:geolong)";
           
            //set the fields values 
            $stmt= $this->db->prepare($sql);
            $stmt->bindValue(":fullname", trim($this->fullname));
            $stmt->bindValue(":email", addslashes(strtolower(trim($this->email))));
            $stmt->bindValue(":id", Validator::UniqueKey(8));
            $stmt->bindValue(":password",sha1($this->password));                
            $stmt->bindValue(":geolat", "".$this->lat);
            $stmt->bindValue(":registerDate",Validator::Now());
            $stmt->bindValue(":status", "0");
            $stmt->bindValue(":geolong", trim("".$this->long));       
            
            
            $abool = $stmt->execute();
            if($abool && $stmt->rowCount()>0){
                $okay=true;            
              }  
              
     
        
        return $okay;
        
    }
    
   
    
    

}
