#!/bin/bash

phpunit --coverage-html ./report APCUserCacheTest.php

exit $?