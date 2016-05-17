<?php
macro {  T_VARIABLE·A ??= } >> {
    T_VARIABLE·A =  T_VARIABLE·A ??
}

$a ??= 123;
echo $a;