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
     * @param string     $host
     * @param \Exception $previous
     *
     * @return ErrorException
     */
    public static function failed($host, \Exception $previous)
    {
        return new self(sprintf('Failed to query the server "%s"', $host), $previous->getCode(), $previous);
    }

    /**
     * @param string $host
     *
     * @return ErrorException
     */
    public static function invalidResponse($host)
    {
        return new self(sprintf('Invalid response from the server "%s"', $host));
    }
}
