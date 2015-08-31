<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchUser
 *
 * @author hacks
 */
include_once("interfaces/TryFetch.php");
include_once("interfaces/TryValidate.php");
include_once("appdata/tables/UserTable.php");


class TryFetchUser implements TryFetch, TryValidate 
   {
    
    private $__message;
    private $database;
 
    public function __construct() {        
   
      
    }
    
    public function fetch($email) {         
     
       if($this->validated($email))
       {
           //Try Create the database table 
            $this->database = new  Database();      
            $table= new UserTable();          
            $table->Create($this->database);
        
      
          $columns=" ".UserTable::FullName.",".
                  UserTable::ID.",".
                  UserTable::Photo_URL.",".
                  UserTable::RegisterDate.",".   
                  UserTable::Gender.",".     
                  UserTable::LastUpdateDate.",".  
                  UserTable::VerificationCode.",".    
                  UserTable::UserName." ";                 
                   
           $query= "Select $columns from ".UserTable::TableName." where ".UserTable::UserName." =:email OR ".UserTable::ID."=:email";
           $stmt= $this->database->prepare($query);
           $stmt->bindValue(":email", addslashes(strtolower(trim($email))));
           $aBool= $stmt->execute();
           if($aBool)
           {
               if($stmt->rowCount()>0)
               {
                    $fetchRow= $stmt->fetch(Database::FETCH_ASSOC);
                    $fetchRow[UserTable::Photo_URL]=IMAGE_PROFILE_DIR.$fetchRow[UserTable::Photo_URL];
                    return $fetchRow;
                     
               }else{
                  $this->__message = "There is no username with the give email address [$email]";
               }
           }
           
       }
       
       return null;
        
    }

   public   function validated($email) {
        $okay=false;
        
        if(Validator::IsEmpty($email))
        {
           $this->__message="Invalid email or user id  provided";         
           
        }else{
            $okay=true;
        }
        
        return $okay;
        
    }
    
    function getMesssage()
    {
        return $this->__message;
    }

//put your code here
}
