<?php
macro {
    T_VARIABLE·A =~ s/T_STRING·B/T_STRING·C/
} >> {
    T_VARIABLE·A =
        preg_replace(
            '/'. ··stringify(T_STRING·B) .'/u',
            ··stringify(T_STRING·C) ,
            T_VARIABLE·A
        )
}

$abc = 'abc';
$abc =~ s/a/v/;
echo $abc;