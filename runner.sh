#!/bin/bash

clear

phpunit --verbose --colors --coverage-html ./report APCUserCacheTest.php

exit $?