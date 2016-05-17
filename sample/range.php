<?php
macro {
    foreach ( T_LNUMBER·S .. T_LNUMBER·E as T_VARIABLE·INDEX ) {
        ···body
    }
} >> {
    foreach ( range(T_LNUMBER·S , T_LNUMBER·E) as T_VARIABLE·INDEX ){
        ···body
    }
}

foreach( 1 .. 10 as $_ ){ echo $_; }