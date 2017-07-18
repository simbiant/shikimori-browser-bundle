<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Config tree builder.
     *
     * Example config:
     *
     * anime_db_shikimori_browser:
     *     host: 'https://shikimori.org'
     *     prefix: '/api/'
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        return (new TreeBuilder())
            ->root('anime_db_shikimori_browser')
                ->children()
                    ->scalarNode('host')
                        ->cannotBeEmpty()
                        ->defaultValue('https://shikimori.org')
                    ->end()
                    ->scalarNode('prefix')
                        ->cannotBeEmpty()
                        ->defaultValue('/api/')
                    ->end()
                ->end()
            ->end()
        ;
    }
}
