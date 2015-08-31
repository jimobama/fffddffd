<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadUserPhotoController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUploadUserProfilePhoto.php");
include_once("Models/TryFetchUserProfilePhoto.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryCheckProfileTypes.php");

class TryUploadUserPhotoController implements IController {
    
    
    public function Index() {
        
         $email=  HttpResquestHandler::getParam("email");
         $password=  HttpResquestHandler::getParam("password");
         $imagebase64 = HttpResquestHandler::getParam("image");
         
      
         return $this->upLoad($email,$password, $imagebase64);
    }

    
    
   // The method will upload the image to the give directory and stored it againts the user  account
    private function upLoad($email,$password, $imagebase64)
    {
        
       $response=array();
       $response["success"]=0;
       $jsonView = new JsonViewer();
       
      $tryLogin= new  TryUserLogin($email,$password);
      
      if($tryLogin->isExists()){

        $tryUser= new  TryFetchUser();
        $user=  $tryUser->fetch($email);

        if($user !=null){

            //the user exists
             $jsonuser= json_encode($user);
             $phpuser= json_decode($jsonuser);            
             $tryuploadImage= new TryUploadUserProfilePhoto($phpuser->ID, $imagebase64);
             
             $path= $tryuploadImage->update();
             
            if($path!=null)
            {                
              $response["success"]=1;
              $response["src_img"]=$path;
            }else{
             
             $response["error_message"]="Could not upload file not file given";
              
            }

        }
      }else{
        
            $response["error_message"]="Session expired , please relogin with your devices";
      }
     $jsonView->setContent($response);
      
      return  $jsonView;
        
    }
//put your code here
}
