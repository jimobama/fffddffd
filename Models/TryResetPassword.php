<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryResetPassword
 *
 * @author hacks
 */

include_once("Models/TryUpdate.php");

class TryResetPassword implements TryUpdate{
    
   
    private $email;
    private $db;
    
   function __construct($email)
    {
           
        $this->email=$email;
        $this->db= new Database();
        
    }
    
    public function update() { 
        
        $query="Update ".UserTable::TableName." set ".UserTable::VerificationCode."=:code where  ".
                UserTable::UserName."=:email";
        
        $stmt= $this->db->prepare($query);
        $code=Validator::UniqueKey(4);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":code",$code);   
        
        if($stmt->execute() && $stmt->rowCount()>0)
        {
            return $code; 
        }else{
            return null;
        }
        
    }

//put your code here
}
