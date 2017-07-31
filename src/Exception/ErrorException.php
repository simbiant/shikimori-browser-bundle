<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Exception;

class ErrorException extends \RuntimeException
{
    /**
     * @param \Exception $previous
     *
     * @return ErrorException
     */
    public static function failed(\Exception $previous)
    {
        return new self('Failed to query the server', $previous->getCode(), $previous);
    }

    /**
     * @return ErrorException
     */
    public static function invalidResponse()
    {
        return new self('Invalid response from the server');
    }
}
