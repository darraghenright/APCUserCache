<?php

require_once __DIR__ . '/src/Hoolux/APC/UserCache.php';

use Hoolux\APC\UserCache;

$userCache = new UserCache();

var_dump($userCache);

$userCache->add('campaign_1', 1);
$userCache->add('campaign_2', 2);
$userCache->add('campaign_3', 3);

$userCache->add('foo', 4);
$userCache->add('bar', 5);

//$result = $userCache->getItems();
//var_dump($result);

$filter = function($item) {
    return preg_match('/campaign_\d+/', $item['key']);
};

$result = $userCache->getItemsByFilter($filter);
var_dump($result);
