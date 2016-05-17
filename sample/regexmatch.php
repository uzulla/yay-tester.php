<?php
macro {
    T_VARIABLE·A =~ /T_STRING·B/
} >> {
    preg_match( '/'. ··stringify(T_STRING·B) .'/u' ,T_VARIABLE·A )
}

$abc = 'abc';
if($abc =~ /a/){
    echo 1;
}