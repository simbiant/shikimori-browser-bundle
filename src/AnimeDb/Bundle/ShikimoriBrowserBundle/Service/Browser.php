<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Guzzle\Http\Client;

/**
 * Browser
 *
 * @link http://shikimori.org/
 * @link http://shikimori.org/api/doc
 * @package AnimeDb\Bundle\ShikimoriBrowserBundle\Service
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Browser
{
    /**
     * Host
     *
     * @var string
     */
    private $host;

    /**
     * API host
     *
     * @var string
     */
    private $api_host;

    /**
     * API path prefix
     *
     * @var string
     */
    private $api_prefix;

    /**
     * HTTP client
     *
     * @var \Guzzle\Http\Client
     */
    private $client;

    /**
     * Construct
     *
     * @param \Guzzle\Http\Client $client
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
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get API host
     *
     * @return string
     */
    public function getApiHost()
    {
        return $this->api_host;
    }

    /**
     * Get data from path
     *
     * @param string $path
     *
     * @return mixed
     */
    public function get($path)
    {
        /* @var $response \Guzzle\Http\Message\Response */
        $response = $this->client->get($this->api_prefix.$path)->send();
        if ($response->isError()) {
            throw new \RuntimeException('Failed to query the server '.$this->api_host);
        }
        $body = @json_decode($response->getBody(true), true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            throw new \RuntimeException('Invalid response from the server '.$this->api_host);
        }
        return $body;
    }
}