<?php

/*The file contains user operations to lohin when the application is first login
 * 
The default index web page of the Devent applicatin 
 * 
 *  */

require_once("Controllers/IController.php");




class IndexController implements IController {
 
    
    function __construct() {
       
       
     }
     
     
     //The only function to call to logic user 
    function Index()
      {
       echo "<h1>Site still under construction...</h1>";
       echo "<a href='app-release.apk'>Download now</a>";
       echo "<h5>Webmaster @Jimotech...</h5>";
  
      }
      
}
