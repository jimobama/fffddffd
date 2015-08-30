<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUploadEventPicture
 *
 * @author hacks
 */

include_once("Models/TryUpdate.php");
include_once("Models/EventTable.php");

class TryUploadEventPicture implements TryUpdate {
    
    private $event_id;
    private $base64image;
   const TARGET_IMAGE_PATHS="uploads/events/images/";
    
    public function __construct($event_id,$base64) {
        $this->event_id=$event_id;
        $this->base64image=$base64;
      
    }
    
    public function update() {
        
        $filename=$this->event_id.".png";
        $okay=false;
        if( $this->isUploaded($filename,$this->base64image))
        {
            $db= new Database();
            $sql="UPDATE ".EventTable::TableName." set ".EventTable::Image."=:image_filename WHERE ".EventTable::Id."=:event_id";
            
            $stmt= $db->prepare($sql);
            $stmt->bindValue(":image_filename",$filename);
            $stmt->bindValue(":event_id",$this->event_id);
            if($stmt->execute())
            {
                $okay=true;
            }
            else{
                print_r($stmt->errorInfo());
            }
        }
        
        return $okay;
    }
    
    private function isUploaded($filename,$base64Image)
    {
        
        $targetPath=   TryUploadEventPicture::TARGET_IMAGE_PATHS.$filename;
        $handle=null;
        $okay=false;
       
        if(file_exists($targetPath))
        {
           $handle=  fopen($targetPath,"wb")  ;        
        }else{
         $handle =  fopen($targetPath,"wb");
        }
      
         $byteWrite=  fwrite($handle, base64_decode($base64Image));
        
         fclose($handle);
         if( $byteWrite >0)
         {           
             $okay=true;
         }else{
             unlink($targetPath);
         }
         
         return $okay;
        
    }

//put your code here
}
