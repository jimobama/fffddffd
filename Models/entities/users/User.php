<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author hacks
 */



 class User {
    //put your code here
     
     private $fullname=null;
     private $id=null;
     private $gender=null;
     private $email=null;
     private $create_date=null;
    private  $last_update=null;
    private   $verificationCode=null;
     
     function __construct($json="") {
         
         if($json !="" && $json!=null){
             $this->parserJson($json);
         }
     }
     
     
     private function parserJson($json){
         
     
        
         $php_object =  json_decode($json);          
         
         $this->fullname= $php_object->fullname;
         $this->id= $php_object->id;
         $this->email= $php_object->email;         
         $this->gender  = $php_object->gender;
         $this->create_date= $php_object->create_date;
         $this->last_update= $php_object->last_update;
         $this->verificationCode= $php_object->vercode;
         return $this;
         
     }
     
    
     public function setFullname($fullname){
         $this->fullname=$fullname;
         return $this;
     }
     
     
    public  function getFullname(){
         return $this->fullname;
     }
     
     
      public function setId($id){
         $this->id=$id;
         return $this;
     }
       
    public  function getId(){
         return $this->id;
     }
      
     
    public function setGender($gender){
         $this->gender=$gender;
         return $this;
     }
    public function getGender(){
        return $this->gender;
    } 
    
     public function setEmail($email){
         $this->email=$email;
         return $this;
     }
    public function getEmail(){
        return $this->email;
    }
    
    //The is timespan in unix time
     public function setCreateDate($timespan){
         $this->create_date=$timespan;
         return $this;
     }
    public function getCreateDate(){
        return $this->create_date;
    }
    
     //The is timespan in unix time
     public function setLastUpdate($timespan){
         $this->last_update=$timespan;
         return $this;
     }
    
    public function getLastUpdate(){
        return $this->last_update;
    }
    
    public function getVerificationCode(){
        return $this->verificationCode;
    }
      public function setVerificationCode($code){
        $this->verificationCode=$code;
        return $this;
    }
     
}
