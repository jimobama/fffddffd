<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUploadUserProfilePhoto
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUpdate.php");
include_once("Models/TryCheckerTypers.php");
include_once("Models/UserTable.php");



class TryUploadUserProfilePhoto implements TryUpdate {
    

  private $imagebase64=null;
  private $userid=null;
 
  const TARGET_IMAGE_PATHS="uploads/profiles/images/";
  
    function __construct($phpuser, $imagebase64)
    {
         $this->imagebase64=$imagebase64;
         $this->userid =$phpuser;
       
    }
    
  //Upload the image to the server
    public function update() {
      
      
     if(TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS !=null && is_dir(TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS))
        {
            //save into database 
            $filename=$this->userid.".JPG";
            
            if($this->uploadImage($this->imagebase64,$filename))
                {
               
                $db= new Database();
                if($db !=null)
                    {
                    
                   $query ="Update ".UserTable::TableName." set ".UserTable::Photo_URL."= :photo_name where ".UserTable::ID." =:id";
                   $stmt= $db->prepare($query);
                   $stmt->bindValue(":id", $this->userid);
                   $stmt->bindValue(":photo_name",$filename);
                 
                   if($stmt->execute())
                   {                       
                       return TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS.$filename;
                   }else{
                       print_r($stmt->errorInfo());
                   }

               } 
              
            }
        }
        
        return null;
      
    }
    
    
    
    private function uploadImage($imagebase64,$filename)
    {
        
        if(is_file(TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS.$filename))
        {
            unlink(TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS.$filename);
        }
        $handle= fopen(TryUploadUserProfilePhoto::TARGET_IMAGE_PATHS.$filename, "wb");
        
        $binary = base64_decode($imagebase64);
        $sizeWritten= fwrite($handle, $binary)   ;       
      
       if($sizeWritten!=false)
       {
           $okay=true;
       }
       
       fclose($handle);
       return  $okay;
        
    }
    
//upload the file into the server
    


//put your code here
}
