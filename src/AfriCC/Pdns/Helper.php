<?php

/**
 * This file is part of the africc/pdns-client library.
 *
 * (c) Gunter Grodotzki <gunter@afri.cc>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace AfriCC\Pdns;

class Helper
{
    /**
     * returns canonical domain (e.g. always returns root dot)
     *
     * @param string $name
     *
     * @return string
     */
    public static function canonical($name)
    {
        if (substr($name, -1) !== '.') {
            return $name . '.';
        }

        return $name;
    }

    /**
     * returns class name of given object (excluding namespace)
     *
     * @param object $object
     *
     * @return string
     */
    public static function className($object)
    {
        return substr(strrchr(get_class($object), '\\'), 1);
    }
}
