<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Tested and working
 */

/**
 * Description of CreateUserControler
 *
 * @author hacks
 */
include_once("Controllers/IController.php");
include_once("Models/TryCreateUser.php");
include_once("Models/TryFetchUser.php");
include_once("Models/TrySendEmail.php");

class TryCreateUserController implements IController{
    
    private $message="";
    public function Index() {        
        
        $email= HttpResquestHandler::RequestParams("email");
        $fullname= HttpResquestHandler::RequestParams("fullname");
        $password= HttpResquestHandler::RequestParams("password"); 
        return $this->Create($email,$fullname,$password);
        
    }

    
    //Create new user accunt
    
    public function Create($email,$fullname,$password,$long=0,$lat=0)
    {
        
        $jsonView = new JsonViewer();
        $response= array();
        
      
       
       $tryFetchUser= new  TryFetchUser(new UserTable());
   
       $valid=  $this->validate($email,$fullname,$password);
       
       //try to fetch the user with the email address if it return null that means the email does not exist
       if($tryFetchUser->fetch($email)==null &&  $valid)
       {
            $user= new  TryCreateUser($email,$fullname,$password,$long,$lat);
     
       
           $abool= $user->create();
           if($abool){
               
                $response["success"]=1;                
                $user= $tryFetchUser->fetch($email);
                $response["user"]=$user;
                
                $this->sendWelcomeMessage($password, json_encode($user));
           }else{
                $response["success"]=0;
                $response["error_message"]="report this problem please!";
           }
           
       }else{
           //if its returns a valid that means the email address already exists
           $response["success"]=0;
           if($valid){
                $response["error_message"]="The  [$email] account already exists ";
           }else{
               $response["error_message"]=$this->message;
           }
       }
      
       $jsonView->setContent($response);
       
       return  $jsonView ;
        
    }
//put your code here
    
    
    private function validate($email,$fullname,$password)
    {
        
        $okay=false;
        
         if(!Validator::isEmail($email))
         {
             $this->message="Invalid email[$email] entry";
         }elseif(!Validator::IsWord($fullname))
         {
               $this->message="Invalid name entry [$fullname] ";
         }else if(!Validator::IsWord($password))
         {
             $this->message="Password should be a combination of letters and words";
         }else{
             $okay=true;
         }
         
        return $okay;
        
    }
    
    //Do care if it send or not
    private function sendWelcomeMessage($raw_password, $json)
    {
        
        $user = json_decode($json);
        
        if($user !=null)
        {
            $message = "Dear $user->Fullname, \n\t Thanks for register with DEvent , we intend to bring you new events around the world to you.\n"
                    . "The DEvents is a social application that search events around in your location  with just a clicked.\n"
                    . "\n <br> "
                    . "Below is your username and password"
                    . "<br>"
                    . "Username = $user->Email\n"
                    . "Password = $raw_password"
                    . "\n"
                    . "To learn more on how to used DEvent please visit http://www.DEvents.com\n";
            
            $from="DEventsAdmins@NoReply.com";
            $to=$user->Email;
            $name=$user->Fullname;
            $subject="DEvent account created comfimation";
            
            
            $trysent = new   TrySendEmail($message, $from, $to ,$name,$subject);
            $trysent->update();
         
        }
    }
}
