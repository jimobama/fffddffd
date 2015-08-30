<?php

/**
 * The file contact a validator that will validate fields 
 */

class Validator {

    function AutoGenerator($length = 10) {
        $result = '';
        $data = '';
        for ($i = 0; $i < $length; $i++) {
            $case = mt_rand(0, 1);
            switch ($case) {
                case 0:
                    $data = mt_rand(0, 9);
                    break;
                case 1:
                    $alpha = range('a', 'z');
                    $item = mt_rand(0, 25);
                    $data = strtoupper($alpha[$item]);
                    break;
            }
            $result .= $data;
        }
        return $result;
    }

    static function UniqueKey($length = 10) {
        $id = Validator::AutoGenerator($length);
        return $id;
    }

    static private $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November',
        12 => 'December');

    static function isEmail($aEmail) {
        $ePattern = "/^[a-zA-Z0-9\_\.]+@[a-zA-Z\.\_0-9]+\.[a-zA-Z0-9]{2,3}$/";
        if (preg_match($ePattern, $aEmail) == true) {
            return true;
        } else {
            return false;
        }
        return true;
    }

 

    static function IsNumber($aStringValue) {
        $pattern = "/^[0-9\.]+$/";
        if (preg_match($pattern, $aStringValue)) {
            return true;
        }
        return false;
    }

    static function IsWord($word) {
        $pattern = "/^[ a-zA-Z\_]+[ a-zA-Z0-9\.\_]+$/";
        if (preg_match($pattern, trim($word))) {
            return true;
        }
        return false;
    }

    static final function isPostcode($postcode) {
//UK post validator
        $pattern = "/^[a-zA-Z]{2}[ ]{0,1}[0-9]{1,2}[ ]{0,1}[A-Za-z0-9]+$/";
        if (preg_match($pattern, $postcode)) {
            return true;
        }
        return false;
    }
  static final function IsEmpty($field)
       {
                    
                if(trim($field)=="" || $field ==null)
                {
                    return true;
                }
                return false;
           
       }
       


   

    PUBLIC static function IsDate($date) {
        $okay = false;
        $dd = "";
        $mm = "";
        $yyyy = "";
        if (is_string($date)) {
//language dd/mm/yyyy
            $parseStr = trim($date);
            $type = 0;
            for ($var = 0; $var < strlen($date); $var++) {
                $char = $parseStr[$var];
                switch ($char) {
                    case "/": {
                            $type++;
                        }break;
                    default: {
                            switch ($type) {
                                case 0: {
                                        if (strlen(trim($dd)) <= 1) {
                                            $dd = $dd . $char;
                                        }
                                    }break;
                                case 1: {
                                        if (strlen(trim($mm)) <= 1) {
                                            $mm = $mm . $char;
                                        }
                                    }break;
                                case 2: {
                                        if (strlen(trim($yyyy)) <= 3) {
                                            $yyyy = $yyyy . $char;
                                        }
                                    }break;
                                default:
                                    break;
                            }
                        }break;
                }
            }
        }
        if ((strlen($dd) == 2) && ((strlen($mm)) == 2) && ( (strlen($yyyy) == 2) || (strlen($yyyy) == 4) )) {
            $okay = true;
        }
        return $okay;
    }

    public static function IsTime($time) {
        $okay = false;
//Grammar syntax hh:mm:ss
        $hh = "";
        $mm = "";
        $ss = "";
        if (is_string($time)) {
//0= hh , 1= mm, 2 mm
            $current = 0;
            $parserStr = trim($time);
            for ($var = 0; $var < strlen($parserStr); $var++) {
                $char = $parserStr[$var];
                switch ($char) {
                    case ':': {
                            $current++;
                        }break;
                    default: {
                            switch ($current) {
                                case 0: {
                                        if (strlen($hh) <= 2) {
                                            $hh = $hh . $char;
                                        }
                                    }break;
                                case 1: {
                                        if (strlen($mm) <= 2) {
                                            $mm = $mm . $char;
                                        }
                                    }break;
                                case 2: {
                                        if (strlen($ss) <= 2) {
                                            $ss = $ss . $char;
                                        }
                                    }break;
                                default: {
                                        
                                    }break;
                            }
                        }break;
                }
            }
        }
        if (strlen($hh) == 2 | strlen($mm) == 2) {
            if (strlen($ss) == 2 | (strlen($ss) == 0)) {
                $okay = true;
            }
        }
        return $okay;
    }

    
   static function Now()
   {
     $now =strtotime("now"); 
    
     return $now;
   }
}

//end class
