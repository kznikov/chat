<?php

declare(strict_types = 1);

namespace App\Lib\Misc;

/**
 * Class Container
 */
class Container
{

    /**
     * Container instance
     * @var
     */
    private static $instance;

    /**
     * Container data
     * @var array
     */
    private $data = [];

    /**
     * Constructor
     * @param $items
     */
    public function __construct($items = [])
    {
        $this->replace($items);
    }

    /**
     * Get container instance
     * @return Container
     */
    public static function getInstance(): Container
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add singleton instance to the container
     * @param $key
     * @param $callable
     * @return void
     */
    public function singleton($key, $callable)
    {
        $instance = call_user_func($callable, []);

        $this->set($key, $instance);
    }

    /**
     * Replace container items
     * @param $items
     * @return false|void
     */
    public function replace($items)
    {
        if (!$items) {
            return false;
        }

        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Get all elements as array
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Get container element keys
     * @return int[]|string[]
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Remove container element
     * @param $key
     * @return void
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Check if container has a key
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Add new element to the container
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    /**
     * Get element from the container
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * Get elements count
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Clear the container
     * @return void
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * Magic get element
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        if ($this->data[$key]) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Magic set element
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Magic unset element
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

}
