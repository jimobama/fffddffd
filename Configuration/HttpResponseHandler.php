<?php
require_once("Configuration/IResponseHandler.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HttpResponseHandler
 *
 * @author obaro
 */
class HttpResponseHandler implements IResponseHandler{
    //put your code here
     private  $request=null;
     private $controller=null;
     private $action =null;
    function __construct(IResquestHandler &$handler) {
        $this->request= $handler;       
    }
  
    /*Accessor that return the current view*/
    
public  function getResponseView()
    {
        
        $viewer= $this->_process();       
        return $viewer;
    }
    
    
private function _process()
    {
        if($this->request->isValid())
        {
           $this->controller =$this->request->getController();
           $this->action = $this->request->getAction();          
        }
        return   $this->_run();
    }
    
   
private  function _run()
    {
    if(class_exists($this->controller))
        {
            $clsObject = new $this->controller();          
            if(is_object($clsObject))
            {
              return $this->_exec($clsObject);
            }
           
        }
        
        return null;
    }
    
 // rThe method return this the view 
private function _exec($clsObject)
    {
         $method = new ReflectionMethod($this->controller,$this->action);
         $view =  $method->invokeArgs($clsObject, $this->request->Parameters());
         return $view;
    }

  

}
