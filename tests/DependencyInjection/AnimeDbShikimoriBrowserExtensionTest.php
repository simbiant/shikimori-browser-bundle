<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Tests\DependencyInjection;

use AnimeDb\Bundle\ShikimoriBrowserBundle\DependencyInjection\AnimeDbShikimoriBrowserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AnimeDbShikimoriBrowserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private $container;

    /**
     * @var AnimeDbShikimoriBrowserExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->container = $this->getMock(ContainerBuilder::class);
        $this->extension = new AnimeDbShikimoriBrowserExtension();
    }

    public function config()
    {
        return [
            [
                [],
                'https://shikimori.org',
                '/api/',
            ],
            [
                [
                    'anime_db_shikimori_browser' => [
                        'host' => 'http://shikimori.org',
                        'prefix' => '/api/v2/',
                    ],
                ],
                'http://shikimori.org',
                '/api/v2/',
            ],
        ];
    }

    /**
     * @dataProvider config
     *
     * @param array  $config
     * @param string $host
     * @param string $prefix
     */
    public function testLoad(array $config, $host, $prefix)
    {
        $browser = $this->getMock(Definition::class);
        $browser
            ->expects($this->at(0))
            ->method('replaceArgument')
            ->with(1, $host)
            ->will($this->returnSelf())
        ;
        $browser
            ->expects($this->at(1))
            ->method('replaceArgument')
            ->with(2, $prefix)
            ->will($this->returnSelf())
        ;

        $this->container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('anime_db.shikimori.browser')
            ->will($this->returnValue($browser))
        ;

        $this->extension->load($config, $this->container);
    }
}
