<?php

var_dump(apc_cache_info('user'));

$array = array(
    'foo' => 1,
    'bar' => 2,
    'baz' => 3,
);


var_dump(apc_store($array, 'sf'));


$apc = new APCIterator('user');

while ($apc->valid()) {
    echo 'x';
    var_dump($apc->current(), $apc->key());
    $apc->next();
}

