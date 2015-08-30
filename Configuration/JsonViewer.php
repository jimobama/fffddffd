<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class JsonViewer implements IViewer
{
     protected $content=null;
    
  function displayContent()
    {
      //Setting the Http header 
      if(!headers_sent()){
      header("Content-Type:application/json");
      header("Access-Control-Allow-Origin: *");//to allow client to use httprequest
      header("Access-Control-Allow-Credentials: true");
      header("Cache-Control: max-age=3600, public");
      }
      //print it out
     
      print_r($this->content);  
    }
    
    
    function setContent( $jsonObject=null)
    {
        $response=array();
        if($jsonObject!=null && (is_object($jsonObject) || is_array($jsonObject)))
        {
            
           $this->content= json_encode($jsonObject,128);  
            $response["Response"]=$this->content;
           return ;
        }
       
        $this->buildEmpty($jsonObject);       
       
    }
    
    
private function buildEmpty($jsonObject)
 {
     $response=array();
     $response["response"]= $jsonObject;
     if( $response["response"] !=null){
         $this->content =  json_encode($response,128);
     }
 }
    
    
}