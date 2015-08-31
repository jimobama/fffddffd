<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryAuthenticateUser
 *
 * @author hacks
 */

include("Controllers/IController.php");
include("Models/user_classes/TryUserLogin.php");
include("Models/user_classes/TryFetchUser.php");
include("Models/entities/users/User.php");


class TryAuthenticateUserController implements IController {
    
    
    public function Index() {
        
        $email =  HttpResquestHandler::getParam("email");
        $password =  HttpResquestHandler::getParam("password");
         
        $response =array();
         $response["success"]=0;
         
         $tryuser= new TryFetchUser();
         $userdb= $tryuser->fetch($email);
         
         if($userdb !=null)
         {
            $user= new User(json_encode($userdb));
            
            $tryLogin = new TryUserLogin($user->getId(), $password);
            if($tryLogin->isExists())
            {
             $response["success"]=1; 

            }else{
              $response["error_message"]="Login details is not current or you have not yet verify your account.";
            }
         }else{
              $response["error_message"]="Invalid Username or Password";
         }
        
        $jsonView = new JsonViewer();
        $jsonView->setContent($response);
        
        return  $jsonView;
        
    }

//put your code here
}
