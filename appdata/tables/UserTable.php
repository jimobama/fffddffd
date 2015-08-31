<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Handle all user tables database , this provide the ability move from one table to other
 */

include_once("interfaces/ITable.php");


class UserTable implements ITable{
    
     const TableName="tbl_user_creation";
     const ID="id";
    const UserName ="email";   
    const FullName="fullname";
    const Photo_URL="photo_url";
    const Status ="status";
    const Gender ="gender";
    const VerificationCode="vercode";   
    const RegisterDate="create_date";
    const LastUpdateDate="last_update";
    
    private $db;
    public function Create(Database $db) {
       if($db==null){
            $db= new Database();
       }
       $this->db=$db;
       
       $this->db->createField(UserTable::ID,"Varchar(20)", "primary key")   
       ->createField(UserTable::UserName,"varchar(40)"," NOT NULL")
       ->createField(UserTable::FullName, "varchar(50)", "NOT NULL")
       ->createField(UserTable::Photo_URL, "varchar(50)", "")
       ->createField(UserTable::Status , "int", "default 0")
       ->createField(UserTable::Gender, "varchar(16)", "default null")   
       ->createField(UserTable::VerificationCode, "varchar(20)", "")
       ->createField(UserTable::RegisterDate, "varchar(50)", "not null")
       ->createField(UserTable::LastUpdateDate, "varchar(50)", "not null")
       ->createField(" UNIQUE (".UserTable::UserName.")", "", "")
       //create the table
       ->createTable(UserTable::TableName);
       
       return $this;
    }
  

}


//Table user login details tracker


class UserLoginTable implements ITable{
    
    const TableName="tbl_user_login";
    const ID="id";
    const Password ="password"; 
    const Level ="level";
   
 
    
    public function Create(\Database $db) {
        
          if($db==null){
            $db= new Database();
       }
       $this->db=$db;
       
       
        $this->db->createField(UserLoginTable::ID,"Varchar(20)", "primary key")        
       ->createField(UserLoginTable::Password, "varchar(50)", "NOT NULL")      
       ->createField(UserLoginTable::Level , "int", "default 0")
       //create the table
       ->createTable(UserLoginTable::TableName);
       
       return $this;
        
    }

}