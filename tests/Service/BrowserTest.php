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
use Guzzle\Http\Client;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class BrowserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $host = 'foo';

    /**
     * @var string
     */
    private $api_prefix = 'bar';

    /**
     * @var Browser
     */
    private $browser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Client
     */
    private $client;

    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->browser = new Browser($this->client, $this->host, $this->api_prefix);
    }

    public function testGetHost()
    {
        $this->assertEquals($this->host, $this->browser->getHost());
    }

    public function testGetApiHost()
    {
        $this->client
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue('baz'))
        ;

        $this->assertEquals('baz', $this->browser->getApiHost());
    }

    public function testSetTimeout()
    {
        $timeout = 123;
        $this->client
            ->expects($this->once())
            ->method('setDefaultOption')
            ->with('timeout', $timeout)
        ;

        $this->assertEquals($this->browser, $this->browser->setTimeout($timeout));
    }

    public function testSetProxy()
    {
        $proxy = '127.0.0.1';
        $this->client
            ->expects($this->once())
            ->method('setDefaultOption')
            ->with('proxy', $proxy)
        ;

        $this->assertEquals($this->browser, $this->browser->setProxy($proxy));
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
        /* @var $response \PHPUnit_Framework_MockObject_MockObject|Response */
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $response
            ->expects($this->once())
            ->method('isError')
            ->will($this->returnValue($is_error))
        ;

        /* @var $request \PHPUnit_Framework_MockObject_MockObject|RequestInterface */
        $request = $this->getMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($this->api_prefix.$path)
            ->will($this->returnValue($request))
        ;

        if (!$is_error) {
            $response
                ->expects($this->once())
                ->method('getBody')
                ->with(true)
                ->will($this->returnValue($data ? json_encode($data) : $data))
            ;
        }
    }
}
