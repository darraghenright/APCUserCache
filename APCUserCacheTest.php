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
            throw new \RuntimeException(
                'APC extension is not installed.'
            );
        }

        if (1 !== (int) ini_get('apc.enable_cli')) {
            throw new \RuntimeException(
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
        $this->userCache->setDefaultTtl(array());
        $this->userCache->setDefaultTtl(0.1);
    }

    /**
     * Setting default ttl with float should throw an Exception.
     *
     * @group               Ttl
     * @expectedException   InvalidArgumentException
     */
    public function testSetDefaultTtlWithFloatShouldThrowInvalidArgumentException()
    {
        $this->userCache->setDefaultTtl(0.1);
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
     * @group               Keys
     */
    public function testGetKeysShouldReturnArray()
    {
        $keys = $this->userCache->getKeys();
        $this->assertTrue(is_array($keys));
    }
}
