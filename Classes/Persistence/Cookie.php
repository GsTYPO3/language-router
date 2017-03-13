<?php
namespace NIMIUS\LanguageRouter\Persistence;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Cookie class.
 *
 * An abstraction layer utilizing a namespaced cookie
 * for persisting data.
 */
class Cookie implements SingletonInterface
{
    /**
     * @var string
     */
    protected static $namespace = 'languagerouter';

    /**
     * Class constructor.
     */
    public function __construct()
    {
        if (!is_array($_COOKIE[self::$namespace])) {
            $_COOKIE[self::$namespace] = [];
        }
    }

    /**
     * Stores the given value.
     *
     * @param string $key
     * @param mixed $valie
     * @return void
     */
    public function set($key, $value, $expire = 0)
    {
        setcookie(self::$namespace . '[' . $key . ']', $value, $expire);
    }

    /**
     * Stores the given value as serialized string.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSerialized($key, $value, $expire)
    {
        $this->set($key, json_encode($value), $expire);
    }

    /**
     * Retrieves values for the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key = null)
    {
        if ($key) {
            return $_COOKIE[self::$namespace][$key];
        } else {
            return $_COOKIE[self::$namespace];
        }
    }

    /**
     * Retrieves unserialized values for the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function getUnserialized($key)
    {
        return json_decode($this->get($key), true);
    }

    /**
     * Tests if the given key has data assigned.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return !is_null($_COOKIE[self::$namespace][$key]);
    }

    /**
     * Removes key from session.
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        unset($_COOKIE[self::$namespace][$key]);
    }
}
