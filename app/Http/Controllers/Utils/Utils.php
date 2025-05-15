<?php

namespace App\Http\Controllers\Utils;

use DateTime;

class Utils
{
    public static function rowFormat($index, $value){
        $endWord = explode("_",$index);
        $endWord = last($endWord);
        if($endWord == "date" && !empty($value)){
            $date = DateTime::createFromFormat('Y-m-d', '1899-12-30');
            $date->modify("+$value days");
            return $date->format('m/d/Y');
        }
        return $value;
    }

    public static function dbFormat($index, $value){
        $endWord = explode("_",$index);
        $endWord = last($endWord);
        if($endWord == "date" && !empty($value)){
            $date = DateTime::createFromFormat('Y-m-d', '1899-12-30');
            $date->modify("+$value days");
            return $date->format('Y-m-d');
        }
        return $value;
    }
}
