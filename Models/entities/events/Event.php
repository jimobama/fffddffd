<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author hacks
 */
include_once("Models/TryFetchUserEvent.php");

class Event {
    //put your code here
    private $__id;
    private $__creatorId="";
    private $__createDate="";
    private $__description="";
    private $__duration="";  
    private $__startDate="";
    private $__title="";
    private $__type="0";
    private $__venue="";
    private $__status=0;
    private $base64Image=null;
    private $fees=0.0;
    private $country="";
    private $searchablekeywords="";
    private $going="";
    
  
   function __construct($creatorId=null,$title=null,$venue=null,$type=null)
    {
        $this->__startDate="". (intval(Validator::Now()) + (1* 24 * 60 *60));      
      
        $this->__id= Validator::UniqueKey(14)."".TryFetchUserEvent::count();
        $this->setCreatorId($creatorId);
        $this->setTitle($title);
        $this->setVenue($venue);
        $this->setType($type);
     
        $this->setCreateDate(Validator::Now());          
        
    }
    function setGoing($going)
    {
        $this->going=$going;
        return $this;
    }
    function getGoing(){
        return $this->going;
    }
    
    function getId(){
        return $this->__id;
    }
    function setId($id){
        $this->__id=$id;
         return $this;
    }
    function setCreatorId($creatorid)
    {
        $this->__creatorId=$creatorid;
         return $this;
       
    }
    
    function getCreatorId(){
       
        return $this->__creatorId;
    }
    function getTitle()
    {
         
        return $this->__title;
    }
    function setTitle($title){
        $this->__title=$title;
         return $this;
    }
    function getCreateDate()
    {
        return $this->__createDate;
         return $this;
    }
    
    function setCreateDate($createdate)
    {
        if($createdate!=null){
           $this->__createDate=$createdate;
        }
    }
    
    function getDescription()
    {
        return $this->__description;
    }
    function setDescription($description)
    {
        if($description!=null){
        $this->__description=$description;
        }
    }
   function getDuration()
   {
       return $this->__duration;
   }
   
   function setDuration($duration)
   {
       if($duration!=null){
       $this->__duration=$duration;
       }
   }
   
   function getStartDate()
   {
       return $this->__startDate;
   }
   
   function setStartDate($startDate){
       $this->__startDate=$startDate;
   }
    
 
   
   function getStatus()
   {
       $this->__status;
   }
   
  function setStatus($status)
   {
        if($status!=null){
              $this->__status = $status;
        }
   }
  function getType()
  {
      return $this->__type;
  }
  
  function setType($type){
      if($type!=null){
      $this->__type=$type;
      }
  }
  
  function getVenue()
  {
      return $this->__venue;
  }
  
  function setVenue($venue){
        if($venue!=null){
              $this->__venue=$venue;
        }
  }
  
  function setBase64Image($base64Data)
  {
      $this->base64Image=$base64Data;
  }
  
    function getBase64Image()
  {
     return  $this->base64Image;
  }
  
  function setFees($fees)
  {
      if(is_numeric($fees))
          
      {
          $this->fees= floatval($fees);
      }else{
          $this->fees=0.0;
      }
  }
  
  function getFees(){
      return $this->fees;
  }
  
  function getCurrencyCountry()
  {
      return $this->country;
  }
  function setCurrencyCountry($currentCounrty)
  {
      if($currentCounrty!=null)
      {
          $this->country= $currentCounrty;
      }else{
          $this->country="";
          $this->setFees(0.0);
      }
  }
  
  function setSearchableKeywords($searchvalues)
  {
      if(trim($searchvalues)!=null)
      {
         $this->searchablekeywords= $searchvalues;
      }else{
           $this->searchablekeywords="";
      }
      
  }
  
  function getSearchableKeywords(){
      return $this->searchablekeywords;
  }
}
