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
include_once("Models/user_classes/TryCreateUser.php");
include_once("Models/user_classes/TryFetchUser.php");
include_once("Includes/TrySendEmail.php");

class TryCreateUserController implements IController{
    
    private $message="";
    public function Index() {        
        
        $email= HttpResquestHandler::RequestParams("email");
        $fullname= HttpResquestHandler::RequestParams("fullname");       
        $gender= HttpResquestHandler::RequestParams("gender"); 
        return $this->Create($email,$fullname, $gender);
    }

    
    //Create new user accunt
    
    public function Create($email,$fullname, $gender)
    {
        
        $jsonView = new JsonViewer();
        $response= array();
        $response["success"]=0;
        $user = new User();
        $user->setEmail($email)
                ->setFullname($fullname)
                ->setId(Validator::UniqueKey())
                ->setGender($gender);
              
       
      
       $valid=  $this->validate($user);
       if($valid){
           
          $response=$this->tryCreate($user);
           
       }else{
          $response["error_message"] =$this->message;
       }
       
       //try to fetch the user with the email address if it return null that means the email does not exist            
       $jsonView->setContent($response);       
       return  $jsonView ;
        
    }
//put your code here
    
    
    private function validate(User $user)
    {
        
        $okay=false;
        if( $user==null){ return $okay;}
        
         if(!Validator::isEmail( $user->getEmail()))
         {
             $email =$user->getEmail();
             if($email=="" || $email==null){
              $this->message="User email address required";
             }else{
             $this->message="Invalid email[$email] entry";
             }
             
         }elseif(!Validator::IsWord($user->getFullname()))
         {
             $fullname=$user->getFullname();
             if($fullname==null || $fullname==""){
                $this->message="User fullname is required!";   
             }else{
             
                 $this->message="Enter a valid user name ";
             }
         }else if(Validator::IsWord($user->getGender()))
         {
             $this->message="Enter your gender";
         }else{
             $okay=true;
         }
         
        return $okay;
        
    }
    
    //the helper method will create the user
    private function tryCreate(User $user)
    {
     $tryFetchUser= new  TryFetchUser();
     $response=array();
     if($tryFetchUser->fetch($user->getEmail())==null)
       {
            $tryCreate= new  TryCreateUser($user);       
            $abool=  $tryCreate->create();
           
           if($abool){
               
                $response["success"]=1;                
                $user= $tryFetchUser->fetch($user->getEmail());
                $response["user"]=$user;
                
                $this->sendWelcomeMessage($user->getPassword(), json_encode($user));
           }else{
                $response["success"]=0;
                $response["error_message"]="report this problem please!";
           }
           
       }else{
           $email=$user->getEmail();
           $response["error_message"]="The [$email] account already exists ";
       }
       return $response;
    }
    
    
    
    //Do care if it send or not
    private function sendWelcomeMessage($raw_password, $json)
    {
        
        $user = new User($json);
        
        if($user !=null)
        {
            $fullname=$user->getFullname();
            $code=$user->getVerificationCode();
           
            $message = "Dear  $fullname, \n\t Thanks for register with DFinder , we intend to bring you new events around the world to you.\n"
                    . "The DEvents is a social application that search events around in your location  with just a clicked.\n"
                    . "\n <br> "
                    . "Below is your username and password"
                    . "<br>"
                    . "Username = $user->Email\n"
                    . "Password = $raw_password"
                    . "\n"
                    . "To verify your account you need to copy this given code below and paste it on the field at your screen\n<br>"
                    . "Verication code : $code";
            
            $from="dfinderadministrator@noreply.com";
            $to=$user->Email;
            $name=$user->Fullname;
            $subject="DFinder account creation confirmation details";
            
            
            $trysent = new   TrySendEmail($message, $from, $to ,$name,$subject);
            $trysent->update();
         
        }
    }
}
