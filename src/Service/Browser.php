<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Exception\ResponseException;
use GuzzleHttp\Client;

class Browser
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param string $host
     * @param string $suffix
     */
    public function __construct(Client $client, $host, $suffix)
    {
        $this->client = $client;
        $this->host = $host;
        $this->suffix = $suffix;
    }

    /**
     * Get data from path.
     *
     * @param string $path
     *
     * @return array
     */
    public function get($path)
    {
        $response = $this->client->request('GET', $this->host.$this->suffix.$path);

        if ($response->isError()) {
            throw ResponseException::failed($this->host);
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            throw ResponseException::invalidResponse($this->host);
        }

        return $body;
    }
}
