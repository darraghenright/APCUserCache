#!/bin/bash

clear

phpunit --verbose --colors --coverage-html ./report HooluxAPCUserCacheTest.php

exit $?