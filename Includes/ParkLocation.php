<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParkLocation
 *
 * @author obaro
 */


class GeoPosition extends Object
{
    public $longtitude;
    public $latitude;
    
    function __construct($long,$lat) {
        $this->longtitude=$long;
        $this->latitude=$lat;
    }

    public function validated() {
        if(is_numeric($this->latitude)  && is_numeric($this->longtitude))
        {
            return true;
        }
        $this->setError("Invalid geolocation position submited must be a floating number");
       
        return false;
    }

}
class ParkLocation extends Object {
    //put your code here
    
    public $geolocation;
    public $streetname;
    public $streetImage;
    public $creator_id;
    public $parkUniqueId;
    public $date_added;
    
    function __construct()
    {  
       $this->parkUniqueId=Validator::UniqueKey(10);
       $this->geolocation= new GeoPosition(0.0000,0.0000);
       $this->date_added= Validator::Now();
    }
    
    function setLocation($long,$lat)
    {
        $this->geolocation->latitude=$lat;
        $this->geolocation->longtitude=$long;
    }

    public function validated() {
        $okay=false;
        
        if(Validator::IsNumber($this->streetname) || trim($this->streetname)=="" )
        {
           $this->setError("Invalid street name") ;
        }else if(!is_file ($this->streetImage)){
             $this->setError("Street photo is need to identify the location please!") ;
        }elseif(!$this->geolocation->validated())
        {
            $this->setError($this->geolocation->getError()) ; 
        }
        else{$okay=true;
        
        }
        return $okay;
    }

}
