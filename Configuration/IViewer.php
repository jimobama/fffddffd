<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Viewer
 *
 * @author obaro
 */
interface IViewer {
    //put your code here
  
    
    function displayContent();    
    
    function setContent( $jsonObject=null);
 

}
