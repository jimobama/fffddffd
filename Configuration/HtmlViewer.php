<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HtmlViewer
 *
 * @author hacks
 */
class HtmlViewer implements IViewer {
    
    public function __construct() {
     if(!headers_sent()){
        header("Content-Type:text/html");
        header("Access-Control-Allow-Origin: *");//to allow client to use httprequest
        header("Access-Control-Allow-Credentials: true");
        header("Cache-Control: max-age=3600, public");
      }
    }
    
    public function displayContent() {
       
      //print it out
    }

    public function setContent($jsonObject = null) {
        
    }

//put your code here
}
