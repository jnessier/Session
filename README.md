# Session
[![Build Status](https://travis-ci.org/Neoflow/Session.svg?branch=master&service=github)](https://travis-ci.org/Neoflow/Session)
[![Coverage Status](https://coveralls.io/repos/github/Neoflow/Session/badge.svg?branch=master&service=github)](https://coveralls.io/github/Neoflow/Session?branch=master)
[![Latest Stable Version](https://poser.pugx.org/neoflow/session/v?service=github)](https://packagist.org/packages/neoflow/session)
[![Total Downloads](https://poser.pugx.org/neoflow/session/downloads?service=github)](//packagist.org/packages/neoflow/session)
[![License](https://poser.pugx.org/neoflow/session/license?service=github)](https://packagist.org/packages/neoflow/session)

Session service for Slim 4 and similar [PSR-15](https://www.php-fig.org/psr/psr-15/) compliant frameworks and apps.

## Table of Contents
- [Requirement](#requirement)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Contributors](#contributors)
- [History](#history)
- [License](#license)

## Requirement
* PHP >= 7.3

## Installation
You have 2 options to install this library.

Via Composer:
```bash
composer require neoflow/session
```

Or manually add this line to the `require` block in your `composer.json`:
```json
"neoflow/session": "^2.0.0"
```

## Configuration
The following instructions based on [Slim 4](http://www.slimframework.com), in combination with
 [PHP-DI](https://php-di.org), but should be adaptable for any PSR-11/PSR-15 compliant frameworks and libraries.

Add the service `Neoflow\Session\Session` and middleware `Neoflow\Session\Middleware\SessionMiddleware`
 to the container definitions...
```php
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use Neoflow\Session\Middleware\SessionMiddleware;
use Psr\Container\ContainerInterface;

return [
    // ...
    SessionInterface::class => function () {
        return new Session([
            // Session options
        ]);
    },
    SessionMiddleware::class => function (ContainerInterface $container) {
        $session = $container->get(SessionInterface::class);
        return new SessionMiddleware($session);
    },
    // ...
];
```
...and register the middleware, to autostart the session when it got dispatched. 
```php
use Neoflow\Session\Middleware\SessionMiddleware;

$app->add(SessionMiddleware::class);
```

The service `Neoflow\Session\Session` supports the following options:

| Key | Type | Description | Default |
|---|---|---|---|
| `name` | string | Name of the session cookie. | `"sid"` |
| `autoRefresh` | bool | Refresh of session lifetime after each request. | `true` |
| `cookie['lifetime']` | array | Lifetime of the session cookie in seconds | `3600` |
| `cookie['path']` | array | Path to set in the session cookie | `"/"` |
| `cookie['domain']` | array | Domain to set in the session cookie | `null` |
| `cookie['secure']` | array | Set `true` to sent session cookie only  over secure connections | `false` |
| `cookie['httponly']` | array | Set `false` to make session cookie accessible for scripting languages | `true` |
| `cookie['samesite']` | array | Set `"Strict"` to prevent the session cookie be sent along with cross-site requests | `"Lax"` |
| `iniSettings[]` | array | [PHP session settings](https://www.php.net/manual/en/session.configuration.php), beginning with with `session. | [] |

When your DI container supports inflectors (e.g. [league/container](https://container.thephpleague.com/3.x/inflectors/)),
 you can optionally register `Neoflow/Session/SessionAwareInterface` as inflector to your container definition.

Additionally, you can also use `Neoflow/Session/SessionAwareTrait` as a shorthand implementation of
 `Neoflow/Session/SessionAwareInterface`.

## Usage
Examples how to handle the session:
```php
// Destroy session.
$destroyed = $session->destroy();

// Generate new session id.
$id = $session->generateId();

// Get session cookie.
$cookie = $session->getCookie();

// Get session id.
$id = $session->getId();

// Get session name.
$name = $session->getName();

// Get session status.
$status = $session->getStatus();

// Check whether session is started.
$isStarted = $session->isStarted();

// Set session cookie.
$options = [ // Cookie options
    'lifetime' => 3600,
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
];
$session->setCookie($options);

// Set session name.
$name = 'sid' // Session name
$session->setName($name);

// Start session.
$started = $session->start();
```

Examples how to manage the session data:

T B D

```php
// Get session value by key, or default value when key doesn't exists 
$value = $session->get('key', 'default');

// Check whether session value exists by key
$exists = $session->exists('key');

// Set key and value of session data
$overwrite = true;
$session = $session->set('key', 'value', $overwrite);

// Push session value to the end of an indexed array by key
$session->set('array', []);
$session->push('array', 'value1');

// Check whether session value is empty
$empty = $session->empty('key');

// Delete session value by key
$deleted = $session->delete('key');

// Merge recursively multiple keys and values of session data
$recursive = true;
$flash = $session->merge([
    'key' => 'value',
    'key2' => [
       'key3' => 'value3'     
    ]
], $recursive);

// Get session data as array
$array = $session->toArray();

// Apply a callback with arguments to the session data
$result = $session->apply(function (Neoflow\Session\SessionInterface $session, string $arg1, string $arg2) {
    // Your custom code
}, [
    'arg1',
    'arg2'
]);

// Iterate trough the session data
$result = $session->each(function (string $key, $value) {
    // Your custom code
});
```

## Contributors
* Jonathan Nessier, [Neoflow](https://www.neoflow.ch)

If you would like to see this library develop further, or if you want to support me or show me your appreciation, please
 donate any amount through PayPal. Thank you! :beers:
 
[![Donate](https://img.shields.io/badge/Donate-paypal-blue)](https://www.paypal.me/JonathanNessier)

## History
A long time ago in a galaxy far, far away.... oh sorry, wrong chapter. :stuck_out_tongue_winking_eye: 

Currently, Slim 4 doesn't support an out-of-the-box solution for session handling and flash messages. 
Sure, there are plenty of composer packages and libraries in the wild of the internet. 
Unfortunately, none of them has session handling combined with flash messages in an easy and simple way. 
This circumstance led me to develop this PSR-15 compliant session library for Slim 4. 
Inspired by the slimness of the framework itself.

## License

Licensed under [MIT](LICENSE). 

*Made in Switzerland with :cheese: and :heart:*
