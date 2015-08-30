<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryCheckerTypers
 *
 * @author hacks
 */
interface  TryCheckerTypers {
    //put your code here
    
 function isAllowed();
 function addExtension($ext);
 function uploadImage($image_tem_dir,$img_source_path);
  


}
