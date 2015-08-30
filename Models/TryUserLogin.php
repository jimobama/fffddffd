<?php

/*
 * 
 * Comments
 * 
 */

include_once("Models/ILogin.php");
include_once("Models/UserTable.php");

class TryUserLogin implements ILogin{
    //put your code here
     private $username;
     private $password;
     private $db =null;
     protected $userinformation=null;
     
    public function __construct($username, $password) {
        
        $this->username=$username;
        $this->password =$password;
        $this->db= new Database();
        
    }
    
    //The method is override and check if the username and password given in the constructor is valid
    public function isExists(){
        
        $okay= false;
        if($this->db !=null)
        {
            //Only create the table once
           $userTable= new UserTable();
           $this->db->Create($userTable);
           
           $sql= "Select *From ".UserTable::TableName
                   ." where (".UserTable::UserName." = :username AND ".UserTable::UserPassword."=:password)";
           
           //Add value to database 
           $stmt= $this->db->prepare($sql);
           $stmt->bindValue(":username", addslashes(strtolower(trim($this->username))));
           $stmt->bindValue(":password",(sha1($this->password)));
           
           //if the execution was successfully and the affected row is more than one
            if($stmt->execute() && $stmt->rowCount()> 0 )
            {
              
                $okay=true;
                
            }
            
            
          
        }
        
        return $okay;
    }

  

}
