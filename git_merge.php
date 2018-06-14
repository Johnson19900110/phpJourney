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


if(!isset($options['m'])) {
    echo 'Be lacking of arguments:m' . PHP_EOL;
    return;
}

$command = 'git add .';
getCommand($command);

$command = 'git commit -m "' . $options['m'] . '"';
getCommand($command);

$command = 'git checkout ' . $options['t'];
getCommand($command);


$command = 'git pull origin ' . $options['t'];
getCommand($command);

$command = 'git merge ' . $options['s'];
getCommand($command);

$command = 'git push origin ' . $options['t'];
getCommand($command);

$command = 'git checkout ' . $options['s'];
getCommand($command);

function getCommand($command)
{
    exec($command, $res);
    print_r($res);
}

