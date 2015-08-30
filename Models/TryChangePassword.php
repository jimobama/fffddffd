<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tryChangePassword
 *
 * @author hacks
 */
class TryChangePassword implements TryUpdate{
    
    private $resetCode=null;
    private $password=null;
    private $email=null;
    
    public function __construct($email,$resetCode, $password) {
      $this->email=$email;
      $this->password=$password;
      $this->resetCode=$resetCode;
    
      
    }
    public function update() {
        
        $db= new Database();
        
        $sql="UPDATE ".UserTable::TableName ." set ".UserTable::UserPassword." = :password,".UserTable::VerificationCode." = ' '"
                . " where  ".UserTable::UserName." =:email AND ".UserTable::VerificationCode." = :code";
        
        $stmt= $db->prepare($sql);
        $stmt->bindValue(":email", addslashes(strtolower(trim($this->email))));
        $stmt->bindValue(":password",sha1($this->password));
        $stmt->bindValue(":code",$this->resetCode);
         $status=   $stmt->execute();
         
         if($status){
            
             if( $stmt->rowCount()>0)
             {             
              return true;
             }
         }else{
            print_r($stmt->errorInfo());
         } 
         
         
        
         return false;        
    }

//put your code here
}
