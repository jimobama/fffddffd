<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUpdateUserEvent
 *
 * @author hacks
 */
include_once("Models/TryUpdate.php");

class TryUpdateUserEvent implements TryUpdate{

    
     private $event=null;
     
     public function __construct($event) {
         $this->event=$event;
     }
    
    public function update() {
        
    }

//put your code here
}
