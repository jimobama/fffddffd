<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryUploadEventWallPhotoController
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryUploadEventPicture.php");
include_once("Models/TryUserLogin.php");
include_once("Models/TryFetchUserEvent.php");

class TryUploadEventWallPhotoController implements IController {
    public function Index() {
        
         $email = HttpResquestHandler::RequestParams("user_id");
         $password = HttpResquestHandler::RequestParams("user_password");
         $eventId = HttpResquestHandler::RequestParams("event_id");
         $imagebase64 = HttpResquestHandler::RequestParams("image");         
         
         return $this->uploadEventImage($email,$password,$eventId,$imagebase64);
        
    }
    
    
    private function uploadEventImage($email,$password,$eventId,$imagebase64)
    {
        $response=array();
         $response["success"]=0;
        
        //upload the 
        $tryLogin = new TryUserLogin($email, $password);
        if($tryLogin->isExists())
        {
            //check if the event exist
           $event= TryFetchUserEvent::FetchById($eventId);
           if($event!=null) {
               print_r($event);
            $tryUploadEventPicture= new TryUploadEventPicture($eventId,$imagebase64);
            
            $result= $tryUploadEventPicture->update();
            if($result)
            {
                 $response["success"]=1;
                 $response["message"]="upload successful";
            }else{
                 $response["error_message"]="There is no image or file to upload";
            }
          }else{
               $response["error_message"]="There is no event found with the given $eventId id";  
          }
            
            
        }else{
              $response["error_message"]="User did not exist , have to login first to be able to upload event image";
        }
        
        $json= new JsonViewer();
        $json->setContent($response);        
        return $json;
        
    }

//put your code here
}
