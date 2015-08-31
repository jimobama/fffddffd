<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author obaro
 */


include_once("interfaces/ITable.php");

class Database extends PDO {
    //put your code here
            //put your code here
      private $queryString="";
       public function __construct() {
       //connect to the server and to the specific database
           
            try
            {
                 parent::__construct("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
            }
            catch(PDOException $err)
            {
                $response =array();
                $response["Status"]="200";
                
                $error= array();
                $error["message"]= $err->getMessage();
                $error["code"]=$err->getCode();               
                $response["error"]= $error;  
                $json= json_encode($response);
                die("<pre>$json</pre>");
            }
       }//end functions
       
       public function createField($name, $type, $constraints)
       {
           if(Validator::IsWord(trim($name)))
           {
               $name=  addslashes($name);
               $type=  addslashes($type);
               $constraints=  addslashes($constraints);
                $fields ="$name  $type  $constraints";
                $this->buildQuery($fields);
           }
           return $this;
           
       }
       public function createTable($tablename)
               
       {
           
          $query= "Create Table If Not Exists $tablename (".$this->queryFields().")";   
        
          $this->runCommand( $query);
         
       
        
       }
      
       public function Create(ITable $table)
       {
          if($table !=null){
              
              $table->Create($this);
          }
           
       }
  private   function buildQuery($field)
     {
         if(is_string($field))
         {
             $this->queryString= $this->queryString."$field ,";
         }
     }
 private    function  queryFields()
       {
           $this->queryString = trim( $this->queryString,",") ;
           return  $this->queryString;
       }
     
       
       public function runCommand($query_string)
       {
             
         $stmt= $this->prepare($query_string);         
         $abool= $stmt->execute();
         if(!$abool)
         {
              print_r($stmt->errorInfo());
         }
       }
     
}
