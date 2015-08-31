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
        
        $email= HttpResquestHandler::getParam("email");
        $fullname= HttpResquestHandler::getParam("fullname");       
        $gender= HttpResquestHandler::getParam("gender"); 
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
              
   
       if($this->validate($user)){
           
           //call the mthod to try an create the user if the email did not exists
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
     $response["success"]=0;
     if($tryFetchUser->fetch($user->getEmail())==null)
       {
            $user->setVerificationCode(Validator::UniqueKey(4));
            $tryCreate= new  TryCreateUser($user);       
            $abool=  $tryCreate->create();
           
           if($abool){
               
                $response["success"]=1;                
                $user= $tryFetchUser->fetch($user->getEmail());
                $response["user"]=$user;
                $this->sendWelcomeMessage(json_encode($user));
           }else{
               
                $response["error_message"]="report this problem please!";
           }
           
       }else{
           $email=$user->getEmail();
           $response["error_message"]="The [$email] account already exists ";
       }
       return $response;
    }
    
    
    
    //Do care if it send or not
    private function sendWelcomeMessage($json)
    {
       
        
        $user = new User($json);
        
        
     
       
        if($user !=null)
        {
            $fullname=$user->getFullname();
            $code=$user->getVerificationCode();
            $email=$user->getEmail();           
           
            $message = "Dear  $fullname, \n\t Thanks for register with DFinder , we intend to bring you new events around the world to you.\n"
                    . "The DEvents is a social application that search events around in your location  with just a clicked.\n"
                    . "\n <br> "
                    . "Below is your username and password"
                    . "<br>"
                    . "Username = $email<br>"
                    . "Verification code: = $code"
                    . "\n<br>"
                    . "To verify your account you need to copy the above given code below and paste it on the field at your screen\n<br>";
                  
            
            $from="dfinderadministrator@noreply.com";
            $to=$email;
            $name=$fullname;
            $subject="DFinder account creation confirmation details";
            
            
            $trysent = new   TrySendEmail($message, $from, $to ,$name,$subject);
            $trysent->update();
         
        }
    }
}
