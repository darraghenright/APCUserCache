<?php

/**
 * APCUserCache
 *
 * A wrapper for APC's user cache
 * implementing a subset of APC
 * functions for managing cache items.
 *
 * @author  Darragh Enright <darragh@hoolux.com>
 */
class APCUserCache extends \APCIterator
{
    /**
     * Time To Live
     *
     * Store var in the cache for ttl seconds.
     * After the ttl has passed, the stored variable
     * will be expunged from the cache (on the next request).
     * If no ttl is supplied (or if the ttl is 0), the value
     * will persist until it is removed from the cache
     * manually, or otherwise fails to exist in the cache.
     *
     * @var integer
     */
    protected $ttl;

    /**
     * Constructor.
     *
     * Check APC Extension is loaded.
     * Call the APCIterator constructor.
     * Set default ttl of 0 seconds.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new \RuntimeException(
                'APC extension is not installed'
            );
        }

        parent::__construct('user');
        $this->setDefaultTtl(0);
    }

    /**
     * Set default ttl for stored items.
     *
     * @param  integer $ttl
     * @throws \InvalidArgumentException
     */
    public function setDefaultTtl($ttl)
    {
        if (!is_int($ttl)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not a valid ttl value', $ttl)
            );
        }

        $this->ttl = $ttl;
    }

    /**
     * Get default ttl for stored items.
     *
     * @return integer
     */
    public function getDefaultTtl()
    {
        return $this->ttl;
    }

    /**
     * Get keys for all currently stored items.
     *
     * @return array
     */
    public function getKeys()
    {
        $keys = array();
        $this->rewind();

        while ($this->valid()) {
            $keys[] = $this->key();
            $this->next();
        }

        return $keys;
    }
    /**
     * Get all currently stored items.
     *
     * @return array
     */
    public function getItems()
    {
        $items = array();
        $this->rewind();

        while ($this->valid()) {
            $items[] = $this->current();
            $this->next();
        }

        return $items;
    }

    /**
     * Checks if one ore more APC keys exist.
     *
     * @param  mixed $keys A string, or an array of strings.
     * @return boolean|array
     */
    public function exists($key)
    {
        if (!is_array($key) || !is_string($key)) {
            throw new \InvalidArgumentException(
                'The key provided must be a string or array of strings'
            );
        }

        return apc_exists($key);
    }

    /**
     * Cache a new variable in the data store.
     *
     * Keys are cache-unique, so attempting to use
     * APCUserCache::add() to store data with a key
     * that already exists will not overwrite the
     * existing data, and will instead return false.
     *
     * @param  string  $key   Store the variable using this name
     * @param  boolean $value The variable to store
     * @param  integer $ttl   Time To Live
     * @return boolean|array
     */
    public function add($key, $value = true, $ttl = null)
    {
        return apc_add($key, $value, ($ttl ?: $this->ttl));
    }

    /**
     * Cache a variable in the data store. Existing
     * variables with the same key are overwritten.
     *
     * @param  string  $key   Store the variable using this name
     * @param  boolean $value The variable to store
     * @param  integer $ttl   Time To Live
     * @return boolean|array
     */
    public function store($key, $value = true, $ttl = null)
    {
        return apc_store($key, $value, ($ttl ?: $this->ttl));
    }

    /**
     * Fetch a stored variable from the cache.
     * If an array is passed then each element
     * is fetched and returned.
     *
     * @param  mixed $key The key used to store the value.
     * @return [type]      [description]
     */
    public function fetch($key)
    {
        //apc_fetch( mixed $key [, bool &$success ] )
    }

    /**
     * Removes a stored variable from the cache
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function delete($key)
    {
        apc_delete($key);
    }
}
