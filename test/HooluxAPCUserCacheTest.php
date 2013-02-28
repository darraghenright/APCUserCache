<?php

require_once __DIR__ . '/../src/Hoolux/APC/UserCache.php';

use Hoolux\APC\UserCache;

/**
 * APCUserCacheTest
 *
 * Unit tests for \APCUserCache.
 *
 * @author Darragh Enright <darragh@hoolux.com>
 */
class HooluxAPCUserCacheTest extends \PHPUnit_Framework_TestCase
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
     * Initialise tests:
     *
     * * Create a new APCUserCache instance
     * * CLear user cache (the old fashioned way)
     */
    public function setUp()
    {
        $this->userCache = new UserCache();
        apc_clear_cache('user');
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
        $this->assertInstanceOf('Hoolux\\APC\\UserCache', $this->userCache);
    }

    /**
     *  Object should extend class \APCIterator
     */
    public function testObjectSHouldExtendClassAPCIterator()
    {
        $parent = get_parent_class($this->userCache);
        $this->assertEquals('APCIterator', $parent);
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
     * Method `add()` should cacheNewValueButNotOverwriteExistingValue description]
     * @return [type] [description]
     */
    public function testAddShouldOnlyWriteNewValueButNotOverwriteExistingValue()
    {
        $r1 = $this->userCache->add('key', 1);
        $this->assertEquals($this->userCache->fetch('key'), 1);
        $this->assertTrue($r1);

        $r2 = $this->userCache->add('key', 2);
        $this->assertEquals($this->userCache->fetch('key'), 1);
        $this->assertFalse($r2);
    }

    /**
     * [testStoreShouldWriteNewValueAndOverwriteExistingValue description]
     */
    public function testStoreShouldWriteNewValueAndOverwriteExistingValue()
    {
        $r1 = $this->userCache->store('key', 1);
        $this->assertEquals($this->userCache->fetch('key'), 1);
        $this->assertTrue($r1);

        $r2 = $this->userCache->store('key', 2);
        $this->assertEquals($this->userCache->fetch('key'), 2);
        $this->assertTrue($r2);
    }

    /**
     * [testFetchShouldReturnFalseForNonExistentKey description]
     *
     * @group             Cache
     */
    public function testFetchShouldReturnFalseForNonExistentKey()
    {
        $this->assertFalse($this->userCache->fetch('key'));
    }

    /**
     * [testNonStringKeyThrowsError description]
     *
     * $group             Cache
     * @expectedException \InvalidArgumentException
     */
    public function testNonStringKeyThrowsError()
    {
        $this->userCache->add(1);
    }

    /**
     * Method getKeys() should return an array.
     *
     * @group               Cache
     */
    public function testGetKeysShouldReturnArray()
    {
        $keys = $this->userCache->getKeys();
        $this->assertTrue(is_array($keys));
        // keys are correct?
    }

    /**
     * Method `getItems()` should return an array.
     *
     * @group               Cache
     */
    public function testGetItemsShouldReturnArray()
    {
        $items = $this->userCache->getItems();
        $this->assertTrue(is_array($items));
    }

    /**
     * Method `clear()` should clear user cache.
     *
     * @group               Cache
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
