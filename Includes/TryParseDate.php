<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TryParseDate
 *
 * @author hacks
 */
class TryParseDate {
    //put your code here
    
    
    public static function parser($timestamp)
    {
        $date_t = date("d/m/Y", $timestamp);
       
        if(Validator::IsDate($date_t))
        {
            if(TryParseDate::IsDateInFuture($timestamp)){
                            
                return true;
            }
        }
        
       
        return true;
    }
    
    
    
public  static function IsDateInFuture($timespan) {
             
        $dateFuture = new DateTime();
        $dateFuture->setTimestamp(intval($timespan));
        $dateNow = new DateTime();
        $dateNow->setTimestamp(Validator::Now());
        $interval = date_diff($dateNow,$dateFuture);  
        
        
       $days=  intval($interval->format('%R%a'));
       $intseconds= intval($interval->s);
      if($days >=0   &&  $intseconds>0 )
      {
          return true;
      }
        return false;
    }
}
