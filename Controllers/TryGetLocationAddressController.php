<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryGetLocationAddressController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryFetchLocationBaseOnGeoLocation.php");

class TryGetLocationAddressController implements IController{
    //put your code here
    
    public function Index() {
        
        
         $address=  HttpResquestHandler::RequestParams("address");
         $lat=  HttpResquestHandler::RequestParams("lat");
          $long=  HttpResquestHandler::RequestParams("long");
      
        return $this->getLocationAddress($address,$lat,$long);
        
    }

    
    
    //Now get the address    
    private function getLocationAddress($address,$lat,$long){
        $response=array();
        $response["success"]=0;
        $tryFetchLocation = null; 
        $criterial ="";
         
        
        if($lat !=null  || $long !=null)
        {
         
            $tryFetchLocation= new TryFetchLocationBaseOnGeoLocation();
            $criterial=  ""+urldecode($lat).",".urldecode($long);//get the best fit and match address
            
            
            //fetch the location base on the latitude of the user
        }else{
            //fetch the location base on the address of the user
             $criterial=urldecode($address);
             $tryFetchLocation= new TryFetchLocationBaseOnAddress();
            
              
        }
        
         
       $addresses= $tryFetchLocation->fetch($criterial);
       if($addresses!=null){
           $response["success"]=1;
           $response["addresses"]=$addresses;
           
       }
      
       
       $jsonView = new JsonViewer();
       $jsonView->setContent($response);
       return $jsonView;
        
    }
}
