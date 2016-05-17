<?php
macro { use strict; } >> {
    set_error_handler(function($errno, $errstr, $errfile, $errline){
        throw new \Error($errstr);
    });
}
macro { use warnings; } >> { error_reporting(-1); }

use strict;
use warnings;
echo $a;