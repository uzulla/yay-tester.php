<?php
macro {
    use ·ns()·classname ;
} >> {
    use ·classname ;
    __require( ··stringify(·classname) ) ;
}

function __require($class){
    $array = explode('\\', ltrim($class, '\\'));
    $path  = implode(DIRECTORY_SEPARATOR, $array);
    $filename = $path.'.php';
//    require_once($filename);
    echo $filename; // サンプルなので。
}

use \This\Is\Test;
