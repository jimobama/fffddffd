<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TableAttendance
 *
 * @author hacks
 */
include_once("Models/ITable.php");
class TableAttendance implements ITable {
    const TableName ="event_attendances";
    const EVENT_ID ="event_id";
    const USER_ID ="user_id";
    
    public function Create(\Database $db) {
        
       $db->createField(TableAttendance::EVENT_ID , "Varchar(40)", "NOT NULL");
       $db->createField(TableAttendance::USER_ID, "Varchar(40)", "NOT NULL");
       $db->createField("CONSTRAINT pk_event_goer ", "PRIMARY KEY (".TableAttendance::EVENT_ID.",".TableAttendance::USER_ID.")", "");
        
       $db->createTable(TableAttendance::TableName);
    }

//put your code here
}
