<?php
require_once("Configuration/IResquestHandler.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HttpResquestHandler
 *
 * @author obaro
 */
class HttpResquestHandler  implements IResquestHandler {

    // fields
    private $_website = null;
    private $_reflection = null;
    private $_request_array = null;
    private $_controller = null;
    private $_action = null;
    private $_valid;
    private $params = null;
    private $__methodRef = null;
    private $__parameters = null;

    //constructor
    public function __construct() {
       
        
        $this->_website = \filter_input(INPUT_SERVER, "HTTP_HOST"); 
      
        // get all the request get and post
        $this->_request_array = new ArrayIterator($_REQUEST);
        
       
        $this->params = new ArrayIterator();    
        $this->__parameters = array();
    }

     public function run()
     {
        $this->_parser();
       
     }
     
     
    private function _parser() 
     {
         
        if (($this->_request_array != null) && ($this->_request_array->count() > 0))
            {
               
                $this->_request_array->seek(0);
                $controller = $this->_request_array->current();
              
                $this->_request_array->next();
             //check if there is more parameters in the request   
            if ($this->_request_array->valid())
                {
                    $action = $this->_request_array->current();              
                    $this->loadParams();
                    
                   
                } 
            else {
                 
                $action = DEFAULT_ACTION;
            }
            //load the controller and the action
            
            $this->_controller = $controller;
            $this->_action = $action;
            
            //load the paramters
        
        }else{
           //print_r($this->_request_array);
             $this->_controller =DEFAULT_CONTROLLER;
             $this->_action = DEFAULT_ACTION;
        }
        
       
        
       $this->loadController();
    }

    private function loadController() 
            {
        //make such the controller is not empty
        if( $this->_controller == null){
            $this->_controller =DEFAULT_CONTROLLER;
        }
        
        
        $classController = trim($this->_controller) .trim(CONTROLLER_SUFIX);
        $controller_path = trim(CONTROLLER_PATH . trim($classController). ".php");
       
        //check if the controller exist 
        if (file_exists($controller_path)) {
           
            //include the current controller to work with            
         require_once($controller_path);
            
       
            
            
            //check if the controller class is defined 
            if (class_exists( $classController,true))
                {
                     ;
                    $this->_controller = $classController;
                    $this->_validation();               
                }
          
                          
        }
       
    }

    private function _validation() {
        try {
            //create a class reflection object with given controller
            $this->_reflection = new ReflectionClass($this->_controller);
            //check if the give action request exist 
            $this->_refMethodParser($this->_reflection);
            
        } catch (ReflectionException $ex) {
            $this->_valid = false;
            $this->_errorReporter($ex->getMessage());
        }
    }

    private function _refMethodParser(ReflectionClass $ref) {
         
        try {
            if (!$ref->hasMethod($this->_action)) {
                //do nothing here 
          
                throw new Exception("No such request page [$this->_action]");
            } else {
                
                $this->__methodRef = $ref->getMethod($this->_action);
                if ($this->__methodRef->isPublic()) 
                {
                    $this->_valid = true;
                    $this->_action = $this->__methodRef->getName();
                    
                } else {
                    throw new Exception("The class $this->_controller [$this->_action] method cannot not be called unless its convert to be public ");
                }
            }
        } catch (Exception $errr) {
            die($errr->getMessage());
        }
    }
    
    
    

    public function Parameters() {
        if ($this->__methodRef != null) {
            return $this->_methodBinding($this->__methodRef);
        }
        return array();
       
    }

    private function _methodBinding(ReflectionMethod $ref) 
            {
                $counter = $ref->getNumberOfParameters();
                $requiredParamater = $ref->getNumberOfRequiredParameters();
                $paramCounter = $this->params->count();
                $paramter = array();  
                
                for ($iter = 0; $iter < $this->params->count(); $iter++) {
                        $this->params->seek($iter);
                        $value = trim($this->params->current());
                        $key = trim($this->params->key());
                        if ($value === null || $value === "") {
                            $value = null;
                        }
                        $paramter[$key] = $value;
                    }
                    $this->__parameters = $paramter;
                    
                 if(sizeof($this->__parameters) < $requiredParamater)
                 {       
                    $this->__parameters= $this->initialiseParameters($this->__parameters ,$paramCounter,$requiredParamater);
                 }
       
        return $this->__parameters;
    }

  private function initialiseParameters(array &$arrayObject, $start,$end) 
                {
       
        for ($var = $start; $var < $end; $var++) {
            
            $arrayObject[$var] = null;
        }
        return $arrayObject;
    }

    final public function HasParameters() {
        if ($this->params->count() > 0) {
            return true;
        }
        return false;
    }

    private function loadParams()
    {
        for ($counter = 2; $counter < $this->_request_array->count(); $counter++) 
        {
            $this->_request_array->seek($counter);
            $this->params->offsetSet($this->_request_array->key(), $this->_request_array->current());
           
        }
        
    }

    /* Is valid methods */

    public function isValid() {
        return $this->_valid;
    }

    private function _errorReporter($error) {
// redirection
        echo $error;
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        if ($this->_controller == null) {
            return DEFAULT_ACTION;
        }
        return $this->_action;
    }

    static public function getParam($param) {
        if (isset($_REQUEST[$param])) {
            return $_REQUEST[$param];
        }
        return null;
    }


}
