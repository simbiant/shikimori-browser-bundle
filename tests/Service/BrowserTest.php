<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2014, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Tests\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Browser;
use GuzzleHttp\Client;
use Guzzle\Http\Message\Response;
use Psr\Http\Message\StreamInterface;

class BrowserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $host = 'foo';

    /**
     * @var string
     */
    private $prefix = 'bar';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Client
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Response
     */
    private $response;

    /**
     * @var Browser
     */
    private $browser;

    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->browser = new Browser($this->client, $this->host, $this->prefix);
    }

    public function requests()
    {
        return [
            [
                'GET',
                ['user' => 123],
                ['ignored' => true],
            ],
            [
                'POST',
                ['user' => 123],
                ['ignored' => true],
            ],
            [
                'PUT',
                ['user' => 123],
                ['ignored' => true],
            ],
            [
                'DELETE',
                ['user' => 123],
                ['ignored' => true],
            ],
        ];
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Exception\ResponseException
     * @dataProvider requests
     *
     * @param string $method
     * @param array  $options
     * @param array  $data
     */
    public function testGetFailedTransport($method, array $options, array $data)
    {
        $this->buildDialogue($method, 'baz', true, $data, $options);

        switch ($method) {
            case 'GET':
                $this->browser->get('baz', $options);
                break;
            case 'POST':
                $this->browser->post('baz', $options);
                break;
            case 'PUT':
                $this->browser->put('baz', $options);
                break;
            case 'DELETE':
                $this->browser->delete('baz', $options);
                break;
        }
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Exception\ResponseException
     * @dataProvider requests
     *
     * @param string $method
     * @param array  $options
     */
    public function testGetFailedResponseBody($method, array $options)
    {
        $this->buildDialogue($method, 'baz', false, [], $options);


        switch ($method) {
            case 'GET':
                $this->browser->get('baz', $options);
                break;
            case 'POST':
                $this->browser->post('baz', $options);
                break;
            case 'PUT':
                $this->browser->put('baz', $options);
                break;
            case 'DELETE':
                $this->browser->delete('baz', $options);
                break;
        }
    }

    /**
     * @dataProvider requests
     *
     * @param string $method
     * @param array  $options
     * @param array  $data
     */
    public function testGet($method, array $options, array $data)
    {
        $this->buildDialogue($method, 'baz', false, $data, $options);

        switch ($method) {
            case 'GET':
                $this->assertEquals($data, $this->browser->get('baz', $options));
                break;
            case 'POST':
                $this->assertEquals($data, $this->browser->post('baz', $options));
                break;
            case 'PUT':
                $this->assertEquals($data, $this->browser->put('baz', $options));
                break;
            case 'DELETE':
                $this->assertEquals($data, $this->browser->delete('baz', $options));
                break;
        }
    }

    /**
     * @param string $method
     * @param string $path
     * @param bool   $is_error
     * @param array  $data
     * @param array  $options
     */
    protected function buildDialogue($method, $path, $is_error, array $data = [], array $options = [])
    {
        $body = $this->getMock(StreamInterface::class);
        $body
            ->expects($is_error ? $this->never() : $this->once())
            ->method('getContents')
            ->will($this->returnValue($data ? json_encode($data) : null))
        ;

        $this->response
            ->expects($this->once())
            ->method('isError')
            ->will($this->returnValue($is_error))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with($method, $this->host.$this->prefix.$path, $options)
            ->will($this->returnValue($this->response))
        ;

        if (!$is_error) {
            $this->response
                ->expects($this->once())
                ->method('getBody')
                ->will($this->returnValue($body))
            ;
        }
    }
}
