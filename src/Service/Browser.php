<?php
/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */
namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use Guzzle\Http\Client;

/**
 * Browser.
 *
 * @link http://shikimori.org/
 * @link http://shikimori.org/api/doc
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
    private $api_prefix;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param string $host
     * @param string $api_prefix
     */
    public function __construct(Client $client, $host, $api_prefix)
    {
        $this->client = $client;
        $this->host = $host;
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
        return $this->client->getBaseUrl();
    }

    /**
     * @param int $timeout
     *
     * @return Browser
     */
    public function setTimeout($timeout)
    {
        $this->client->setDefaultOption('timeout', $timeout);
        return $this;
    }

    /**
     * @param int $proxy
     *
     * @return Browser
     */
    public function setProxy($proxy)
    {
        $this->client->setDefaultOption('proxy', $proxy);
        return $this;
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
        $response = $this->client->get($this->api_prefix.$path)->send();
        if ($response->isError()) {
            throw new \RuntimeException('Failed to query the server '.$this->client->getBaseUrl());
        }

        $body = json_decode($response->getBody(true), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            throw new \RuntimeException('Invalid response from the server '.$this->client->getBaseUrl());
        }

        return $body;
    }
}
