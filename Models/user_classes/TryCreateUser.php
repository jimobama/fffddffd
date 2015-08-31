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
require_once("interfaces/TryCreate.php");
require_once("appdata/tables/UserTable.php");
require_once("Models/entities/users/User.php");


class TryCreateUser implements TryCreate{
    //put your code here
    private $user;

    function __construct(User $user) {     
         
       $this->user=$user;
        $this->db = new Database();      
    }

    /*Create a  user data records*/
    public function create() {
        
          
        $this->db->create(new UserTable());
        $okay=false;
       
        $sql= "Insert Into ".UserTable::TableName 
                    ."(".UserTable::FullName.","
                    .UserTable::ID.","
                    .UserTable::UserName.","
                    .UserTable::Gender.","
                    .UserTable::LastUpdateDate.","
                    .UserTable::RegisterDate.","
                    .UserTable::Status.","
                    .UserTable::VerificationCode.")"                    
                    . " Values"
                    . "(:fullname,:id,:email,:gender,:lastupdate,:registerDate,:status,:vcode)";
           
            //set the fields values 
        $now=Validator::Now();
            $stmt= $this->db->prepare($sql);
            $stmt->bindValue(":fullname", trim($this->user->getFullname()));
            $stmt->bindValue(":id", Validator::UniqueKey(8)); 
            $stmt->bindValue(":email", addslashes(strtolower(trim($this->user->getEmail()))));                                
            $stmt->bindValue(":gender", $this->user->getGender());
            $stmt->bindValue(":lastupdate",$now);
            $stmt->bindValue(":registerDate", $now);   
            $stmt->bindValue(":status", 0);
            $stmt->bindValue(":vcode", $this->user->getVerificationCode());
            
            $abool = $stmt->execute();
            if($abool && $stmt->rowCount()>0){
                $okay=true;            
              }  
              
     
        
        return $okay;
        
    }
    
   
    
    

}
