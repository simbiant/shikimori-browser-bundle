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
    private $host = 'bar';

    /**
     * @var string
     */
    private $suffix = 'baz';

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

        $this->browser = new Browser($this->client, $this->host, $this->suffix);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetFailedTransport()
    {
        $this->buildDialogue('baz', true);
        $this->browser->get('baz');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetFailedResponseBody()
    {
        $this->buildDialogue('baz', false);
        $this->browser->get('baz');
    }

    public function testGet()
    {
        $data = ['test' => 123];
        $this->buildDialogue('baz', false, $data);
        $this->assertEquals($data, $this->browser->get('baz'));
    }

    /**
     * @param string $path
     * @param bool $is_error
     * @param mixed $data
     */
    protected function buildDialogue($path, $is_error, $data = null)
    {
        $body = $this->getMock(StreamInterface::class);
        $body
            ->expects($is_error ? $this->never() : $this->once())
            ->method('getContents')
            ->will($this->returnValue($data ? json_encode($data) : $data))
        ;

        $this->response
            ->expects($this->once())
            ->method('isError')
            ->will($this->returnValue($is_error))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->host.$this->suffix.$path)
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
