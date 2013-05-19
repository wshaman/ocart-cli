<?php
class F{
    public static function escape_str($str, $force_strong=False){
        if(!$force_strong){
            $syms = array('\x00', '\n', '\r', '\\', "'", '"', '\x1a');
            $rep =array('\\x00', '\\n', '\\r', '\\\\', "\'", '\"', '\\x1a');
            return str_replace($syms,$rep,$str);
        } else {
            return preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $str);
        }

//        return mysql_real_escape_string($str);
    }

    /**
     * gets item from array or returns $def value
     * @param array $arr
     * @param mixed $key
     * @param bool $def
     * @return bool
     */
    public static function array_get(&$arr, $key, $def=False){
        return (isset($arr[$key])) ? $arr[$key] : $def;
    }

    public static function simplify_string($str){
        $r = str_replace(' ', '', $str);
        return strtolower($r);
    }

    public static function extract_numbers($str, $to_int=False){
        preg_match_all('/\d+/', $str, $matches);
        $s = implode('',$matches[0]);
        if($to_int){
            $s = (int)$s;
        }
        return $s;
    }

    public static function rlog($message){
        if(YIELD_LOG)
            echo $message."\n";
    }

    public static  function logFile($msg, $status, $f_obj){
        if(is_array($msg))
            $msg = implode(DELIMETER_S, $msg);
        $msg = implode(DELIMETER_S,array(mktime(),$msg));
        $st = ' OK ';
        switch($status){
            case STATUS_ERR: $st = 'ERRO'; break;
            case STATUS_WARN: $st = 'WARN'; break;
        }
        $msg = "[{$st}] || {$msg}\n";
        fwrite($f_obj,$msg);
    }
}