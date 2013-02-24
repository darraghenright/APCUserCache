<?php

require_once __DIR__ . '/APCUserCache.php';

/**
 * APCUserCacheTest
 *
 * Unit tests for APCUserCache.
 *
 * @author Darragh Enright <darragh@hoolux.com>
 */
class APCUserCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * TestCase initialisation tasks:
     *
     * * Check APC extension is installed
     * * Check apc.cli mode is enabled
     */
    public static function setUpBeforeClass()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped(
                'APC extension is not installed.'
            );
        }

        if (1 !== (int) ini_get('apc.enable_cli')) {
            $this->markTestSkipped(
                'APC extension is not enabled for CLI.'
            );
        }
    }

    /**
     * Init instance for tests.
     */
    public function setUp()
    {
        $this->userCache = new \APCUserCache();
    }

    /**
     * Integer range provider.
     *
     * @return array An array of integers from -1 - 120
     */
    public function getIntegerRange()
    {
        return array(range(-1, 120));
    }

    /**
     * Float range provider.
     *
     * @return array An array of floats from 0.1 - 3.1
     */
    public function getFloatRange()
    {
        return array(range(0.1, 3.1, 0.2));
    }

    /**
     * Object should be instance of APCUserCache.
     */
    public function testObjectShouldBeInstanceOfAPCUserCache()
    {
        $this->assertInstanceOf('APCUserCache', $this->userCache);
    }

    /**
     * Default ttl should be zero.
     *
     * @group               Constructor
     */
    public function testDefaultTtlShouldBeZero()
    {
        $ttl = $this->userCache->getDefaultTtl();
        $this->assertEquals($ttl, 0);
    }

    /**
     * Default ttl should be settable and gettable.
     *
     * @group               Ttl
     * @dataProvider        getIntegerRange
     * @param integer       $int
     */
    public function testDefaultTtlShouldBeSettableAndGettable($int)
    {
        $this->userCache->setDefaultTtl($int);
        $ttl = $this->userCache->getDefaultTtl();
        $this->assertEquals($ttl, $int);
    }

    /**
     * Setting default ttl with string should throw an Exception.
     *
     * @group               Ttl
     * @expectedException   InvalidArgumentException
     */
    public function testSetDefaultTtlWithStringShouldThrowInvalidArgumentException()
    {
        $this->userCache->setDefaultTtl('foo');
    }

    /**
     * Setting default ttl with float should throw an Exception.
     *
     * @group               Ttl
     * @dataProvider        getFloatRange
     * @expectedException   InvalidArgumentException
     */
    public function testSetDefaultTtlWithFloatShouldThrowInvalidArgumentException($float)
    {
        $this->userCache->setDefaultTtl($float);
    }

    /**
     * Setting default ttl with boolean should throw an Exception.
     *
     * @group               Ttl
     * @expectedException   InvalidArgumentException
     */
    public function testSetDefaultTtlWithBooleanShouldThrowInvalidArgumentException()
    {
        $this->userCache->setDefaultTtl(true);
    }

    /**
     * Method getKeys() should return an array.
     *
     * @group               Data
     */
    public function testGetKeysShouldReturnArray()
    {
        $keys = $this->userCache->getKeys();
        $this->assertTrue(is_array($keys));
    }

    /**
     * Method getItems() should return an array.
     *
     * @group               Data
     */
    public function testGetItemsShouldReturnArray()
    {
        $items = $this->userCache->getItems();
        $this->assertTrue(is_array($items));
    }

    // test add should write new value
    // test add should not rewrite existing value
    // test store should write new value
    // test store should rewrite existing value
    // test fetch should return false for non existent key
    // test fetch should return expected values for existing key
    // test keys returned
    // test non string key throws error

    /**
     * Method `clear()` should clear user cache.
     *
     * @group               Data
     */
    public function testClearShouldClearUserCache()
    {
        $key = (string) time();
        $val = 1;

        $this->userCache->add($key, $val);
        $this->assertEquals($this->userCache->fetch($key), $val);

        $this->userCache->clear();

        $keys = $this->userCache->getKeys();
        $this->assertTrue(empty($keys));
        $this->assertFalse($this->userCache->fetch($key));
    }
}
