<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paralelo
 *
 * @author Allan
 */
class Paralelo {

    //put your code here
    private $data = array();

    public function __set($name, $value) {
        //echo "Setting '$name' to '$value'\n";
        $this->data[$name] = $value;
    }

    public function __get($name) {
        //echo "Getting '$name'\n";
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __toString() {
        return $this->array2json();
    }

    public function array2json() {
        $arr = $this->data;

        if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
        $parts = array();
        $is_list = false;

        //Find out if the given array is a numerical array
        $keys = array_keys($arr);
        $max_length = count($arr)-1;
        if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
                if($i != $keys[$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }

        foreach($arr as $key=>$value) {
            if(is_array($value)) { //Custom handling for arrays
                if($is_list) $parts[] = array2json($value); /* :RECURSION: */
                else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
            } else {
                $str = '';
                if(!$is_list) $str = '"' . $key . '":';

                //Custom handling for multiple data types
                if(is_numeric($value)) $str .= $value; //Numbers
                elseif($value === false) $str .= 'false'; //The booleans
                elseif($value === true) $str .= 'true';
                else $str .= '"' . addslashes($value) . '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

                $parts[] = $str;
            }
        }
        $json = implode(',',$parts);

        if($is_list) return '[' . $json . ']';//Return numerical JSON
        return '{' . $json . '}';//Return associative JSON
    }

}

?>
