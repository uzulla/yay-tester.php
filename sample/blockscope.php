<?php
macro { { ···body } } >> {
    ( function( $arg ) {
        extract( $arg );
        ···body
    } ) ( get_defined_vars() ) ;
}

$abc = 'abc';
{
    $abc = 'def';
    echo $abc, PHP_EOL;
}
echo $abc, PHP_EOL;