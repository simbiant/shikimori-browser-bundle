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

/**
 * Test DependencyInjection.
 */
class AnimeDbShikimoriBrowserExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        /* @var $builder \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder */
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $di = new AnimeDbShikimoriBrowserExtension();
        $di->load([], $builder);
    }
}
