<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException;
use AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\NotFoundException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;

class Browser
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var ErrorDetector
     */
    private $detector;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $app_client;

    /**
     * @param HttpClient    $client
     * @param ErrorDetector $detector
     * @param string        $host
     * @param string        $prefix
     * @param string        $app_client
     */
    public function __construct(HttpClient $client, ErrorDetector $detector, $host, $prefix, $app_client)
    {
        $this->client = $client;
        $this->detector = $detector;
        $this->host = $host;
        $this->prefix = $prefix;
        $this->app_client = $app_client;
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
        $options['headers'] = array_merge(
            [
                'User-Agent' => $this->app_client,
            ],
            isset($options['headers']) ? $options['headers'] : []
        );

        try {
            $response = $this->client->request($method, $this->host.$this->prefix.$path, $options);
        } catch (\Exception $e) {
            throw $this->wrapException($e);
        }

        $content = $response->getBody()->getContents();
        $content = $this->detector->detect($content);

        return $content;
    }

    /**
     * @param \Exception $e
     *
     * @return ErrorException|NotFoundException
     */
    private function wrapException(\Exception $e)
    {
        if ($e instanceof ClientException && $e->getResponse()->getStatusCode() == 404) {
            return NotFoundException::wrap($e);
        }

        return ErrorException::wrap($e);
    }
}
