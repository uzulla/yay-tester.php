<?php
macro {
    (···condition) -> { ···body }
} >> {
    function ( ···condition ) { ···body }
}

$a = ($v)->{ echo $v; };

$a('abc');