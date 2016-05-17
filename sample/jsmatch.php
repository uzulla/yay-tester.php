<?php
macro {
    if( T_VARIABLE·A./T_STRING·B/ ){ ···body }
} >> {
    if( preg_match(
        '/'. ··stringify(T_STRING·B) .'/u' ,
        T_VARIABLE·A ) )
    { ···body }
}

$abc = 'abc';
if($abc./a/){
    echo 1;
}