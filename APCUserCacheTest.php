<?php

require_once __DIR__ . '/APCUserCache.php';

/**
 * APCUserCacheTest
 *
 * @author Darragh Enright <darragh@hoolux.com>
 */
class APCUserCacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * Perform tasks before running tests.
     */
    public static function setUpBeforeClass()
    {
        // check APC is loaded
        // check apc.cli mode
        //
        var_dump(__METHOD__);
    }

    function testShouldPass()
    {
        $this->assertTrue(true);
    }
}
