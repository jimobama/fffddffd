<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IModel
 *
 * @author obaro
 */
abstract class Object {
    //put your code here
    private $error=null;  

    protected function setError($err)
    { if(trim($err)=="")
    return null;
    $this->error=$err;
    }
    public function getError()
    {
    return $this->error;
    }
    abstract function validated();
}
