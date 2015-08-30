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
abstract class IModel extends Object{
    //put your code here
    
     abstract function Create();
     abstract function IsExists();
     abstract function Update($id,Object $object=null);
     abstract function Delete(Object $object=null);
     abstract function Search(Object $object=null);
     
    function validated()
    {
        return false;
    }
}
