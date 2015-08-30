<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchUserProfilePhoto
 *
 * @author hacks
 */
include_once("Models/TryFetch.php");
include_once("Models/UserTable.php");

class TryFetchUserProfilePhoto implements TryFetch {
    
    
   

    public function fetch($email) {
        
        $db= new Database();
        $table= new UserTable();
        $table->Create($db);
        $url=null; 
        
        
         $sql = "Select ".UserTable::Photo_URL." from ".UserTable::TableName.
                 " where ".UserTable::UserName."=:email OR ".UserTable::ID."=:email";
         $stmt= $db->prepare($sql);
         $stmt->bindValue(":email", addslashes(strtolower(trim($email))));
         
         $status=  $stmt->execute();
         if($status)
         {
             $row = $stmt->fetch(Database::FETCH_ASSOC);
             $url = $row[UserTable::Photo_URL];
             if($url !=null)
             {
                 $url =IMAGE_PROFILE_DIR.$url;
             }
         }else{
           print_r($stmt->errorInfo());
         }
        
        return $url;
    }

//put your code here
}
