<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeoController
 *
 * @author obaro
 */
class RegisterUserController  implements IController {
    //put your code here
    
 private    $db=null;
 
 function __construct() {
       $this->db = new Database();       
       //create the table if not exist
       $this->db->createFields("geo_long", "varchar(10)", "");
       $this->db->createFields("geo_lat", "varchar(10)", "");
       $this->db->createFields("email", "varchar(40)"," not null index");
       $this->db->createFields("fullname", "varchar(50)", "");
       $this->db->createFields("password", "varchar(50)", "not null");
       $this->db->createFields("status", "int", "");
        $this->db->createFields("image_path", "varchar(50)", "");
       $this->db->createFields("acct_id", "varchar(16)", "primary key");      
       $this->db->createFields("add_registered", "varchar(50)", "");
       //create the table
       $this->db->createTable("tbl_EventUsers");
  
       include_once("Models/RegisterUserModel.php");
 }
 
function Index()
   {
        $response["success"]=0;    
        $response["content"]= "";
        $response["message"]="";
       
       return $response;
       
   }
   
  public function FindDistance($p1_lat,$p1_long, $p2_lat,$p2_long)
   {
        $response=array();
        $response["success"]=0;    
        $response["content"]= $this->distance($p1_lat, $p1_long, $p2_lat, $p2_long);
        $response["message"]="";    
        
      return $response;
   }
   function Create($userid,$lat,$long,$img)
   {
       //$userid is the application Id code
        $response=array();
        $response["success"]=0;    
        $response["content"]= "";
        $response["message"]="";
    
       $park= new ParkLocation();
       $park->creator_id=$userid;
       $park->setLocation($long, $lat);
       
       $stta = (isset($this->Search($lat,$long)["content"][0]["address"]))?true:false;
       
       $park->streetname=($park->streetname=="" && $stta)?$this->Search($lat,$long)["content"][0]["address"]:"undefined";
       $park->streetImage=$img;
       
        $response["content"]=$park;
       if($park->validated())
       {          
         $model = new ParkModel($park,$this->db);
         $model->Attach($this);
         
         if(!$model->IsExists())
         {
            $status= $model->Create();
            if($status){
             $response["success"]=$status;
             $response["message"]="Successfully added";
            }else
            {
              $response["message"]="could not add the park details";
              $response["success"]=-1;
              $response["content"]=  $model->getError();
            }
         }else 
         {
            $response["success"]=-1;    
            $response["content"]= $park;
            $response["message"]="Someone as already add this address as a park";
         }
         
         
       }else
       {
          $response["content"]=$park;
          $response["success"]=-1; 
          $response["message"]=$park->getError();
       }
       return $response;
   }
   
   
   public function Search($searchAddressOrLat, $long = null)
   {
      
       $content="";
       $status=0;
       if(!Validator::IsNumber($long) && $long==null)
       {
           $content= $this->SearchByAddress($searchAddressOrLat,$status);
       }
      else 
      {  
        $content = $this->SearchByPosition($searchAddressOrLat,$long, $status);
      }
       
       //now we have to process what we really need at an give search
      /*
       * <content>
       *     <
       * </content>
       * **/
    
    $response=array();  
    $content= $this->processRequiredAddress($content);
    $response ["success"]= $status;
    $response["content"]=$content;
   
    
     return  $response;
   }
   

 private function processRequiredAddress($content)
 {
     $result =array();
     $addresses= new   ArrayIterator();
     if(is_array($content))
     {
        $stdObject= new stdClass();
        $stdObject =  json_decode(json_encode($content));
        if(is_object($stdObject->content)){
            $result = ($stdObject->content->results);
        }
     }
     
     if($result !=null)
     {
         foreach($result as $key=>$value)
         {
           $arrayPostcodes=array();
            if($value->address_components !=null)
            {
                for($i=0; $i < (sizeof($value->address_components)); $i++)
                {
                   $object= $value->address_components[$i];
                   $arrayPostcodes["postage"]= $this->getPostAddress($object);
                               
                }
                if($arrayPostcodes["postage"]===null) continue;      
            }   
           
            $arrayPostcodes["address"] =$value->formatted_address;
            $arrayPostcodes["location"]=$value->geometry->location;
            $addresses->append($arrayPostcodes);                   
            
         }
            
         
     }
     
     
     return  $addresses;
     
   
 }
 
 
 private function getPostAddress(stdClass $object)
 {
      $addressPostal=array();     
     
     
      foreach($object->types as $key=>$type)
                    {
                      if($type==="postal_code" || $type==="postal_town"  || $type="neighborhood")
                      {
                        if($type==="postal_code")
                        {
                           $addressPostal["postcode"]= $object->long_name;     
                           $addressPostal["town"]="";
                        }  else {
                            $addressPostal["postcode"]= ""; 
                            $addressPostal["town"]=$object->long_name;
                        }
                        
                      }
                    }
      

      if(sizeof($addressPostal)==0)
          return null;
      return $addressPostal;
 }

}

