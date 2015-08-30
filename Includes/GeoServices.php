<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeoServices
 *
 * @author obaro
 */
class GeoServices {
    //put your code here
  
    
    function __construct() {
       
    }
    
   //The method will return and address base on the given address information 
  private function getGeoPosition($address)
    {   
       $url = "http://maps.google.com/maps/api/geocode/json?sensor=false" . 
        "&address=" . urlencode($address);

       $json = file_get_contents($url);
       $data = json_decode($json, TRUE);
       $status=$data["status"];
            
      return  $data ;
    } 
    
    private  function getaddress($lat,$lng, & $status)
        {
            $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim(urlencode($lat)).
                    ','.trim(urlencode($lng)).'&sensor=false';
            
            $json = @file_get_contents($url);
            $data=json_decode($json);
            $status=$data->status;
            return  $data;
           
        }
       
   protected  function SearchByAddress($address, & $status)
   {
      
       $response=array(); 
       
       
       $response["content"]= $this->getGeoPosition($address,$status);
       
       return $response;
   }
  protected  function SearchByPosition($lat,$lng, & $status)
 {
    
        $response=array();
     
       $response["content"]= $this->getaddress($lat, $lng,$status);
       return $response;
 }
  protected function distance($p1_lat,$p1_long, $p2_lat,$p2_long)
   {
      
       $const_1k_per_mile= 0.6214;
        $const_1k_per_yards=1093.6133;
        $cons_1k_per_feets=3280.8399;
       $l1= new GeoPosition($p1_long,$p1_lat);
       $l2= new GeoPosition($p2_long,$p2_lat);
       
       //caluate it and return a $km
       $km = round($this->DistanceFrom($l1, $l2),2);
       $miles= round($km * $const_1k_per_mile,2) ;
       $yards=round($const_1k_per_yards * $km,2) ;
       $feets= round($cons_1k_per_feets * $km,2);
       
       $result=array();
       $result["distance"]= array("km"=>$km,
                                 "miles"=>$miles,
                                 "yards"=>$yards,
                                 "feets"=>$feets);
       
       $result["location"]= array("from"=>$l1,"to"=>$l2);
        
      
       return $result;
   }
 private  function DistanceFrom(GeoPosition $p1, GeoPosition $p2)
 {
     //using Haversine formula
    $p2->latitude=  ((double)$p2->latitude);
    $p1->latitude= ((double) $p1->latitude);
    $p2->longtitude=  (double)$p2->longtitude ; 
    $p1->longtitude=(double) $p1->longtitude;
    
   
    $R = 6371; // earth's mean radius in km
    $dLat  = $this->radian($p2->latitude - $p1->latitude);  
    $dLong = $this->radian($p2->longtitude -  $p1->longtitude);

    $a = sin($dLat/2) * sin($dLat/2) +  cos($this->radian($p1->latitude)) * 
            cos($this->radian($p2->latitude)) * sin($dLong/2) * sin($dLong/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c;

   return round($d,67,PHP_ROUND_HALF_EVEN);
     
 }
 
 private function radian($x)
         {
            $x=doubleval($x);
             
            return ( $x * pi() / 180);
         
         }
    
}
