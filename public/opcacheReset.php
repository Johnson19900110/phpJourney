<?php
$result = opcache_reset();

if($result) {
    phpinfo();
}else {
    echo 'Failed';
}
