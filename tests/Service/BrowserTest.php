<?php
/**
 * Shmop package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2014, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Tests\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Browser;

/**
 * Test browser
 *
 * @package AnimeDb\Bundle\ShikimoriBrowserBundle\Tests\Service
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class BrowserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Host
     *
     * @var string
     */
    protected $host = 'foo';

    /**
     * API path prefix
     *
     * @var string
     */
    protected $api_prefix = 'bar';

    /**
     * Browser
     *
     * @var \AnimeDb\Bundle\ShikimoriBrowserBundle\Service\Browser
     */
    protected $browser;

    /**
     * Client
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder('\Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->browser = new Browser($this->client, $this->host, $this->api_prefix);
    }

    /**
     * Test get host
     */
    public function testGetHost()
    {
        $this->assertEquals($this->host, $this->browser->getHost());
    }

    /**
     * Test get api host
     */
    public function testGetApiHost()
    {
        $this->client
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue('baz'));
        $this->assertEquals('baz', $this->browser->getApiHost());
    }

    /**
     * Test get failed transport
     *
     * @expectedException RuntimeException
     */
    public function testGetFailedTransport()
    {
        $this->buildDialogue('baz', true);
        $this->browser->get('baz');
    }

    /**
     * Test get failed response body
     *
     * @expectedException RuntimeException
     */
    public function testGetFailedResponseBody()
    {
        $this->buildDialogue('baz', false);
        $this->browser->get('baz');
    }

    /**
     * Test get
     */
    public function testGet()
    {
        $data = array('test' => 123);
        $this->buildDialogue('baz', false, $data);
        $this->assertEquals($data, $this->browser->get('baz'));
    }

    /**
     * Build client dialogue
     *
     * @param string $path
     * @param boolean $is_error
     * @param mixed $data
     */
    protected function buildDialogue($path, $is_error, $data = null)
    {
        $request = $this->getMock('\Guzzle\Http\Message\RequestInterface');
        $response = $this
            ->getMockBuilder('\Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($this->api_prefix.$path)
            ->will($this->returnValue($request));
        $request
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));
        $response
            ->expects($this->once())
            ->method('isError')
            ->will($this->returnValue($is_error));
        if (!$is_error) {
            $response
                ->expects($this->once())
                ->method('getBody')
                ->with(true)
                ->will($this->returnValue($data ? json_encode($data) : $data));
        }
    }
}
