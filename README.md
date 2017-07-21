[![Shikimori](http://anime-db.org/bundles/animedboffsite/images/shikimori.org.png)](https://shikimori.org)

[![Latest Stable Version](https://img.shields.io/packagist/v/anime-db/shikimori-browser-bundle.svg?maxAge=3600&label=stable)](https://packagist.org/packages/anime-db/shikimori-browser-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/anime-db/shikimori-browser-bundle.svg?maxAge=3600)](https://packagist.org/packages/anime-db/shikimori-browser-bundle)
[![Build Status](https://img.shields.io/travis/anime-db/shikimori-browser-bundle.svg?maxAge=3600)](https://travis-ci.org/anime-db/shikimori-browser-bundle)
[![Coverage Status](https://img.shields.io/coveralls/anime-db/shikimori-browser-bundle.svg?maxAge=3600)](https://coveralls.io/github/anime-db/shikimori-browser-bundle?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/anime-db/shikimori-browser-bundle.svg?maxAge=3600)](https://scrutinizer-ci.com/g/anime-db/shikimori-browser-bundle/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/fde73716-6558-46ff-b3a9-f2f989a59d0c.svg?maxAge=3600&label=SLInsight)](https://insight.sensiolabs.com/projects/fde73716-6558-46ff-b3a9-f2f989a59d0c)
[![StyleCI](https://styleci.io/repos/18437335/shield?branch=master)](https://styleci.io/repos/18437335)
[![License](https://img.shields.io/packagist/l/anime-db/shikimori-browser-bundle.svg?maxAge=3600)](https://github.com/anime-db/shikimori-browser-bundle)

Shikimori.org API browser
=========================

Read API documentation here: http://shikimori.org/api/doc

Installation
------------

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer anime-db/shikimori-browser-bundle
```

Configuration
-------------

```yml
anime_db_shikimori_browser:
    # API host
    # As a default used 'https://shikimori.org'
    host: 'https://shikimori.org'

    # Prefix for API resurces
    # As a default used '/api/'
    prefix: '/api/'

    # HTTP User-Agent
    # No default value
    client: 'My Custom Bot 1.0'
```

Usage
-----

First get browser

```php
$browser = $this->get('anime_db.shikimori.browser');
```

List animes ([docs](https://shikimori.org/api/doc/1.0/animes/index))

```php
$animes = $browser->get('animes', ['limit' => 10]);
```

Mark all messages as read ([docs](https://shikimori.org/api/doc/1.0/messages/read_all))

```php
$response = $browser->post('messages/read_all');
```

Update a message ([docs](https://shikimori.org/api/doc/1.0/messages/update))

```php
$response = $browser->patch('messages/12', [
    "message" => [
        "body": "blablabla",
    ],
]);
```

Update a comment ([docs](https://shikimori.org/api/doc/1.0/comments/update))

```php
$response = $browser->put('comments/8', [
    "message" => [
        "body": "blablabla",
    ],
]);
```

Destroy a message ([docs](https://shikimori.org/api/doc/1.0/messages/destroy))

```php
$browser->delete('messages/12');
```

License
-------

This bundle is under the [MIT license](http://opensource.org/licenses/MIT). See the complete license in the file: LICENSE
