<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Tests\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Service\ErrorDetector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ErrorDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private $response;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface
     */
    private $stream;

    /**
     * @var ErrorDetector
     */
    private $detector;

    protected function setUp()
    {
        $this->response = $this->getMock(ResponseInterface::class);
        $this->stream = $this->getMock(StreamInterface::class);
        $this->detector = new ErrorDetector();
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(404))
        ;

        $this->detector->detect($this->response);
    }

    public function testNotContent()
    {
        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue(''))
        ;

        $this->assertEquals([], $this->detector->detect($this->response));
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException
     */
    public function testBadJson()
    {
        $json = '{{}';

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($json))
        ;

        $this->detector->detect($this->response);
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException
     */
    public function testResponseFailed()
    {
        $json = json_encode(['code' => 404]);

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($json))
        ;

        $this->detector->detect($this->response);
    }

    public function testNoErrors()
    {
        $data = ['foo' => 'bar'];
        $json = json_encode($data);

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($json))
        ;

        $this->assertEquals($data, $this->detector->detect($this->response));
    }
}
