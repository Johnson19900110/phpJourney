<?php

$options = getopt("s:t:m:");

if(!isset($options['s'])) {
    echo 'Be lacking of arguments:s' . PHP_EOL;
    return;
}

if(!isset($options['t'])) {
    echo 'Be lacking of arguments:t' . PHP_EOL;
    return;
}

$return = exec('git co ' . $options['s'], $res);
print_r($retrun);

print_r($res);
exit();

// $res = exec('git add .');
// print_r($res);
