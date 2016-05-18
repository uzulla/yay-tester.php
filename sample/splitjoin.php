<?php
macro { split } >> { mb_split }
macro { join } >> { implode }

print_r( split(",", "a,b,c") );
print_r( join(",", ["a","b","c"]) );