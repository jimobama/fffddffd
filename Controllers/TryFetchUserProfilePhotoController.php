<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryFetchUserProfilePhoto
 *
 * @author hacks
 */

include_once("Models/TryFetchUserProfilePhoto.php");
include_once("Models/TryUserLogin.php");
include_once("Controllers/IController.php");

class TryFetchUserProfilePhotoController implements IController {
    
    
    public function Index() {
        
        $email = HttpResquestHandler::getParam("email");
        $password = HttpResquestHandler::getParam("password");
        
        return $this->getProfileImageUrl($email,$password);
        
    }
    
    
    
    private function getProfileImageUrl($email,$password)
    {
        $response=array();
        $response["success"]=0;
        
        $tryLogin = new TryUserLogin($email,$password);
        
        if( $tryLogin->isExists())
        {
         $tryFetchUserPhoto = new TryFetchUserProfilePhoto();           
          $url = $tryFetchUserPhoto->fetch($email);
           $response["success"]=1;
          if($url ==null)
          {
               $url= DEFAULT_IMAGE_PROFILE_URL;
          }
          
           $response["img_src"]= $url;
          
        }else{
           $response["error_message"] ="user authentication fails";
        }
        
     $json = new JsonViewer();
     $json->setContent($response);
     return  $json;
    }

//put your code here
}
