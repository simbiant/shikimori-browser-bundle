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
    private $prefix;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param string $host
     * @param string $prefix
     */
    public function __construct(Client $client, $host, $prefix)
    {
        $this->client = $client;
        $this->host = $host;
        $this->prefix = $prefix;
    }

    /**
     * @param string $resource
     * @param array  $options
     *
     * @return array
     */
    public function get($resource, array $options = [])
    {
        return $this->request('GET', $resource, $options);
    }

    /**
     * @param string $resource
     * @param array  $options
     *
     * @return array
     */
    public function post($resource, array $options = [])
    {
        return $this->request('POST', $resource, $options);
    }

    /**
     * @param string $resource
     * @param array  $options
     *
     * @return array
     */
    public function put($resource, array $options = [])
    {
        return $this->request('PUT', $resource, $options);
    }

    /**
     * @param string $resource
     * @param array  $options
     *
     * @return array
     */
    public function patch($resource, array $options = [])
    {
        return $this->request('PATCH', $resource, $options);
    }

    /**
     * @param string $resource
     * @param array  $options
     *
     * @return array
     */
    public function delete($resource, array $options = [])
    {
        return $this->request('DELETE', $resource, $options);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $options
     *
     * @return array
     */
    private function request($method, $path = '', array $options = [])
    {
        try {
            $response = $this->client->request($method, $this->host.$this->prefix.$path, $options);
        } catch (\Exception $e) {
            throw ResponseException::failed($this->host, $e);
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            throw ResponseException::invalidResponse($this->host);
        }

        return $body;
    }
}
