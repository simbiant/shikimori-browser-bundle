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

class AnimeDbShikimoriBrowserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
    }

    public function testLoad()
    {
        $di = new AnimeDbShikimoriBrowserExtension();
        $di->load([], $this->container);
    }
}
