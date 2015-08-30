<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParkModel
 *
 * @author obaro
 */
class ParkModel extends IModel {
    //put your code here
    private $park = null;
    private $db = null;
    private $controller=null;
    function __construct(ParkLocation $park= null,Database $db = null,RegisterUserController $controller=null ) {
         $this->park= new ParkLocation(0,0);
        $this->park= $park;
        $this->db=$db;
       
       $this->controller =  $controller;
       
            
    }
  public function Attach(RegisterUserController $controller)
  {
      $this->controller =  $controller;     
      
  }
    public function Create() {
        $okay=false;
        
        if($this->db !=null && $this->park !=null)
        {
            //continue
            $query ="Insert into tbl_parklocation (geo_long,geo_lat,streetname,creatorId,approval_count"
                    . ",status,image_path,id,add_registered)"
                    . "values(:geo_long,:geo_lat,:streetname,:creatorId,"
                    . ":approval_count,:status,:image_path,:id,:add_registered)";
            
             $stmt= $this->db->prepare($query);
             $stmt->bindValue(":geo_long", $this->park->geolocation->longtitude);
             $stmt->bindValue(":geo_lat", $this->park->geolocation->latitude);
             $stmt->bindValue(":streetname",$this->park->streetname);
             $stmt->bindValue(":creatorId", $this->park->creator_id);
             $stmt->bindValue(":approval_count", 0);
             $stmt->bindValue(":status", 1);
             $stmt->bindValue(":image_path", $this->park->streetImage);
             $stmt->bindValue(":id", $this->park->parkUniqueId);
             $stmt->bindValue(":add_registered", $this->park->date_added);
       
             $status= $stmt->execute();
              if(!$status)
              {
                print_r($stmt->errorInfo());
              }
        else {
                $okay=true;
             }
        }
        
        
        return $okay;
    }

    public function Delete( Object $object = null) {
        $okay=false;
        
        if($this->db !=null && $object !=null)
        {
             $query = "update tbl_parklocation set status = :status where (id=:id and creatorId=:creatorId)";
             $stmt= $this->db->prepare($query);
             $stmt->bindValue(":id", $object->parkUniqueId);
             $stmt->bindValue(":creatorId", $object->creator_id);
             $stmt->bindValue(":status", 0);
            $status= $stmt->execute();
            if(!$status)
            {
                print_r($stmt->errorInfo());
            }  else {
              $okay=true;  
            }
            
            
        }
        
        return $okay;
    }

    public function IsExists() {
          $okay=false;
          
          if($this->db!=null &&  $this->park != null)
          {
              $query="select *from tbl_parklocation";
              $stmt= $this->db->prepare($query);
              $status= $stmt->execute();
              if(!$status)
              {
                  print_r($stmt->errorInfo());
              }
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
              {
                  // check and calulated for a distance of 100yards and miles 0.06 and 0.11km
                 $long=  $row["geo_long"];
                 $lat=   $row["geo_lat"]; 
                 $result = $this->controller->FindDistance($lat, $long, $this->park->geolocation->latitude, 
                         $this->park->geolocation->longtitude);
                 if($result != null){
                 $yards =(float) $this->getYards($result);
                 
                 if($yards >= 0 &&  $yards  <= 40)
                    {
                        $okay=true;
                    }
                 }
              }
          }
        
        return $okay;
    }

    public function Search(Object $object = null) {
        $Records=new ArrayIterator();
        $yards= Session::get("distance_max");
        
        if($this->db !=null && $object !=null)
        {
             $query="select *from tbl_parklocation order by $lat,$long ";
             $stmt= $this->db->prepare($query);
              $status= $stmt->execute();
              if(!$status)
              {
                  print_r($stmt->errorInfo());
              }
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
              {
                  // check and calulated for a distance of 100yards and miles 0.06 and 0.11km
                 $long=  $row["geo_long"];
                 $lat=   $row["geo_lat"]; 
                 $result = $this->controller->FindDistance($lat, $long, $this->park->geolocation->latitude, 
                         $this->park->geolocation->longtitude);
                 if($result != null){
                 $distances =(float) $this->getYards($result);
                 $yards=(double)$yards;
                 
                 if($yards >=0 && $yards  <= $distsances)
                    {
                        $Records->append($row);
                    }
                 }
              }
        }        
        return $Records;
    }

    public function Update($id, Object $object = null) {
         $okay=false;
        
        if($this->db !=null && $object !=null)
        {
             $query = "update tbl_parklocation set status = :status"
                     . ",approval_count=(approval_count + :approval_count ),image_path=:image_path"
                     . " where (id=:id and creatorId=:creatorId)";
             $stmt= $this->db->prepare($query);
             $stmt->bindValue(":id", $object->parkUniqueId);
             $stmt->bindValue(":creatorId", $object->creator_id);
             $stmt->bindValue(":approval_count",1);
             $stmt->bindValue(":creatorId", $object->creator_id);
             $stmt->bindValue(":status", 0);
            $status= $stmt->execute();
            if(!$status)
            {
                print_r($stmt->errorInfo());
            }  else {
              $okay=true;  
            }
            
            
        }
        
        return $okay;
    }

    
    private function getYards(array $array)
    {
        $result = -1;
        if(is_array($array) && isset($array["content"]))
        {            
            $content= $array["content"];
            
            if(isset($content["distance"]))
            {
                $distance= $content["distance"];
                
                $result= (float)(doubleval($distance["yards"]));
                
            }
            
        }
        
        return (float)$result;
    }
}
