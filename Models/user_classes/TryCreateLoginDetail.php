<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryCreateLoginDetail
 *
 * @author hacks
 */

include_once("interfaces/TryCreate.php");
include_once("interfaces/ILogin.php");

class TryCreateLoginDetail implements TryCreate,  ILogin {
    //put your code here
    private $__id;
    private $__password;
    private $db=null;
    
    public function __construct($id,$password) {
        $this->__id=$id;
        $this->__password=$password;
        $this->db= new Database();      
        $this->db->create(new UserLoginTable());
        
        
    }
    
    public function isExists(){
        
       
        $sql="SELECT * FROM ".UserLoginTable::TableName." WHERE ".UserLoginTable::ID."=:id";
          
        $stmt= $this->db->prepare($sql);
            
            $stmt->bindValue(":id", $this->__id);
         
            
            $status= $stmt->execute();
            if($status )
            {
                if($stmt->rowCount()>0){
                    return true;
                }
            }else{
                print_r($stmt->errorInfo());
            }
            
           return false;
        
    }
   
    public function create() {
      
        
        if($this->db !=null){
         
            
            $sql ="INSERT INTO ".UserLoginTable::TableName." (".UserLoginTable::ID.
                    ",".UserLoginTable::Password.",".
                    UserLoginTable::Level.") VALUES (:id,:password,:level)";
            
            
            $stmt= $this->db->prepare($sql);
            
            $stmt->bindValue(":id", $this->__id);
            $stmt->bindValue(":password", sha1($this->__password));
            $stmt->bindValue(":level", 0);
            
            $status= $stmt->execute();
            if($status)
            {
                return true;
            }else{
                print_r($stmt->errorInfo());
            }
                  
            
        }
        return false;
    }

}
