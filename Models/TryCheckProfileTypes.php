<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryCheckProfileTypes
 *
 * @author hacks
 */
include_once("Models/TryCheckerTypers.php");

class TryCheckProfileTypes implements TryCheckerTypers {
    //put your code here
    private $extensions = null;
    private $ext=null;
    
    
    function __construct($ext=null) {
         $this->extensions= new ArrayIterator();
        if($ext !=null){       
        $this->ext=  addcslashes(trim($ext));
        }
    }
    
    function isAllowed()
    {
        return $this->isFound();
    }
    
    

    public function addExtension($ext) {
        if($ext !=null && trim($ext)=="")
        {
            $this->extensions->append(addslashes(trim($ext)));
        }
        
    }
    
    //The method will check if the extension exists or not
    private function isFound()
            
    {
        $okay=false;
        if($this->extensions !=null){
            //carry out type checking
            foreach($this->extensions as $key=>$value)
            {
                if(trim($value) ==$this->ext)
                {
                    $okay=true;
                    break;
                }
            }
        }
        
        return $okay;
    }
    
public  function uploadImage($image_tem_dir,$img_source_path) {  

    if (move_uploaded_file($image_tem_dir,$img_source_path)) {
        return $img_source_path;
    } else {
        return null;
    }
}

    

}
