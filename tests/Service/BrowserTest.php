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
use AnimeDb\Bundle\ShikimoriBrowserBundle\Service\ErrorDetector;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
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
     * @var string
     */
    private $app_client = 'My Custom Bot 1.0';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|HttpClient
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private $response;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface
     */
    private $stream;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ErrorDetector
     */
    private $detector;

    /**
     * @var Browser
     */
    private $browser;

    protected function setUp()
    {
        $this->client = $this->getMock(HttpClient::class);
        $this->response = $this->getMock(ResponseInterface::class);
        $this->stream = $this->getMock(StreamInterface::class);
        $this->detector = $this->getMock(ErrorDetector::class);

        $this->browser = new Browser($this->client, $this->detector, $this->host, $this->prefix, $this->app_client);
    }

    public function requests()
    {
        return [
            [
                'GET',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => $this->app_client,
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'GET',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => 'Override User Agent',
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'POST',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => $this->app_client,
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'POST',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => 'Override User Agent',
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'PUT',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => $this->app_client,
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'PUT',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => 'Override User Agent',
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'PATCH',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => $this->app_client,
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'PATCH',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => 'Override User Agent',
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'DELETE',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => $this->app_client,
                    ],
                ],
                ['ignored' => true],
            ],
            [
                'DELETE',
                [
                    'user' => 123,
                    'headers' => [
                        'User-Agent' => 'Override User Agent',
                    ],
                ],
                ['ignored' => true],
            ],
        ];
    }

    /**
     * @dataProvider requests
     *
     * @param string $method
     * @param array  $options
     * @param array  $data
     */
    public function testRequest($method, array $options, array $data)
    {
        $path = 'baz';
        $content = json_encode($data);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with($method, $this->host.$this->prefix.$path, $options)
            ->will($this->returnValue($this->response))
        ;

        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->detector
            ->expects($this->once())
            ->method('detect')
            ->with($content)
            ->will($this->returnValue($data))
        ;

        switch ($method) {
            case 'GET':
                $this->assertEquals($data, $this->browser->get($path, $options));
                break;
            case 'POST':
                $this->assertEquals($data, $this->browser->post($path, $options));
                break;
            case 'PUT':
                $this->assertEquals($data, $this->browser->put($path, $options));
                break;
            case 'PATCH':
                $this->assertEquals($data, $this->browser->patch($path, $options));
                break;
            case 'DELETE':
                $this->assertEquals($data, $this->browser->delete($path, $options));
                break;
        }
    }

    /**
     * @return array
     */
    public function methods()
    {
        return [
            ['GET'],
            ['POST'],
            ['PUT'],
            ['PATCH'],
            ['DELETE'],
        ];
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException
     * @dataProvider methods
     *
     * @param string $method
     */
    public function testWrapException($method)
    {
        $path = 'baz';

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with($method, $this->host.$this->prefix.$path, [
                'headers' => [
                    'User-Agent' => $this->app_client,
                ],
            ])
            ->will($this->throwException(new \Exception()))
        ;

        switch ($method) {
            case 'GET':
                $this->browser->get($path);
                break;
            case 'POST':
                $this->browser->post($path);
                break;
            case 'PUT':
                $this->browser->put($path);
                break;
            case 'PATCH':
                $this->browser->patch($path);
                break;
            case 'DELETE':
                $this->browser->delete($path);
                break;
        }
    }
}
