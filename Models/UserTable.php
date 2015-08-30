<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Handle all user tables database , this provide the ability move from one table to other
 */

include_once("Models/ITable.php");


class UserTable implements ITable{
    
   const TableName="tbl_deventuser";
   const UserName ="Email";
   const UserPassword="password";
   const GeoLong= "GeoLong";
   const GeoLat=  "GeoLat";
   const FullName="Fullname";
    const Photo_URL="photo_url";
   const Status ="Status";
   const VerificationCode="verificationCode";
   const ID="ID";
   const RegisterDate="DateRegistered";
    
    private $db;
    public function Create(Database $db) {
       if($db==null){
            $db= new Database();
       }
       $this->db=$db;
       
       $this->db->createFields(UserTable::GeoLong,"Double", "");
       $this->db->createFields(UserTable::GeoLat, "Double", "");
       $this->db->createFields(UserTable::UserName,"varchar(40)"," NOT NULL");
       $this->db->createFields(UserTable::FullName, "varchar(50)", "NOT NULL");
       $this->db->createFields(UserTable::UserPassword, "varchar(50)", "NOT NULL");
       $this->db->createFields(UserTable::Status , "int", "default 0");
       $this->db->createFields(UserTable::ID, "varchar(16)", "primary key");    
       $this->db->createFields(UserTable::VerificationCode, "varchar(20)", "");  
       $this->db->createFields(UserTable::Photo_URL, "varchar(50)", "");  
       
       $this->db->createFields(UserTable::RegisterDate, "varchar(50)", "not null");
       $this->db->createFields(" UNIQUE (".UserTable::UserName.")", "", "");
       //create the table
       $this->db->createTable(UserTable::TableName);
    }

}