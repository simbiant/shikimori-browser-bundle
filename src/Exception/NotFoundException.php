<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Exception;

class NotFoundException extends ErrorException
{
    /**
     * @param \Exception $e
     *
     * @return NotFoundException
     */
    public static function wrap(\Exception $e)
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
