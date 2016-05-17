<?php
macro {
    unless (···expression) { ···body }
} >> {
    if (! (···expression)) { ···body }
}

$isSuccess = false;
unless($isSuccess){
    echo('failed');
}