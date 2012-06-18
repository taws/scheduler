<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utility
 *
 * @author avinueza
 */
class Utility {
    
    public static function FNameFLast($names,$last_names){
        $arraynames=explode(" ", $names);
        $arraylastnames=explode(" ", $last_names);
        return $arraynames[0]." ".$arraylastnames[0];
    }
    public static function FName($names){
        $arraynames=explode(" ", $names);
        return $arraynames[0];
    }
}

?>
