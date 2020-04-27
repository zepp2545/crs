<?php 

if(!function_exists('remove_space')){
    /**
    *
    *
    * @param string $value
    * @return string
    */

    function remove_space($str){
       return preg_replace("/( |　)/", "", $str );
    }

}












?>