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

class ErrorDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ErrorDetector
     */
    private $detector;

    protected function setUp()
    {
        $this->detector = new ErrorDetector();
    }

    public function testNotContent()
    {
        $content = '';

        $this->assertEquals([], $this->detector->detect($content));
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException
     */
    public function testBadJson()
    {
        $content = '{{}';

        $this->detector->detect($content);
    }

    /**
     * @return array
     */
    public function errorResponses()
    {
        return [
            [
                ['code' => 404],
            ],
            [
                ['code' => 500, 'message' => 'Some error message.'],
            ],
        ];
    }

    /**
     * @expectedException \AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException
     * @dataProvider errorResponses
     *
     * @param array $response
     */
    public function testResponseFailed(array $response)
    {
        $content = json_encode($response);

        $this->detector->detect($content);
    }

    public function testNoErrors()
    {
        $data = ['foo' => 'bar'];
        $content = json_encode($data);

        $this->assertEquals($data, $this->detector->detect($content));
    }
}
