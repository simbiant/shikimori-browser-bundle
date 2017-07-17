<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ResponseException;
use GuzzleHttp\Client;

/**
 * Browser.
 *
 * @see http://shikimori.org/
 * @see http://shikimori.org/api/doc
 */
class Browser
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $api_host;

    /**
     * @var string
     */
    private $api_prefix;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param string $host
     * @param string $api_host
     * @param string $api_prefix
     */
    public function __construct(Client $client, $host, $api_host, $api_prefix)
    {
        $this->client = $client;
        $this->host = $host;
        $this->api_host = $api_host;
        $this->api_prefix = $api_prefix;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getApiHost()
    {
        return $this->api_host;
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
        $response = $this->client->request('GET', $this->api_host.$this->api_prefix.$path);

        if ($response->isError()) {
            throw ResponseException::failed($this->api_host);
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            throw ResponseException::invalidResponse($this->api_host);
        }

        return $body;
    }
}
