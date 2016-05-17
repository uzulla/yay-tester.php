<?php
macro {
    foreach ( T_LNUMBER·S .. T_LNUMBER·E as T_VARIABLE·INDEX ) {
        ···body
    }
} >> {
    $__itelatorGenerator = itelatorGenerator(T_LNUMBER·S, T_LNUMBER·E);
    foreach ( $__itelatorGenerator as T_VARIABLE·INDEX ){ ···body }
}

function itelatorGenerator($init, $fin){
    $num = $init;
    while(1){
        if( $fin < $num ) { return $num++; }
        yield $num++;
    }
}

foreach( 1 .. 10 as $_ ){ echo $_; }