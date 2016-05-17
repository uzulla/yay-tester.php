<?php
macro { say ·string()·message } >> {
    echo ·message;
    echo PHP_EOL;
}

say "say!";
say "yes!";