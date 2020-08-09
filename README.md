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

Via composer...
```bash
composer require neoflow/session
```
...or manually download the latest release from [here](https://github.com/Neoflow/Session/releases/).

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
        return new Session([ // Default session options
            'name' => 'sid',
            'autoRefresh' => true,
            'cookie' => [
                'lifetime' => 3600,
                'path' => '/',
                'domain' => null,
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ],
            'iniSettings' => []
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
| `cookie['lifetime']` | int | Lifetime in seconds of the session cookie in seconds | `3600` |
| `cookie['path']` | string | Path to set in the session cookie | `"/"` |
| `cookie['domain']` | string/null | Domain to set in the session cookie | `null` |
| `cookie['secure']` | bool | Set `true` to sent session cookie only  over secure connections | `false` |
| `cookie['httponly']` | bool | Set `false` to make session cookie accessible for scripting languages | `true` |
| `cookie['samesite']` | string | Set `"Strict"` to prevent the session cookie be sent along with cross-site requests | `"Lax"` |
| `iniSettings[]` | array | [PHP session settings](https://www.php.net/manual/en/session.configuration.php), without `session.` | `[]` |

When your DI container supports inflectors (e.g. [league/container](https://container.thephpleague.com/3.x/inflectors/)),
 you can optionally register `Neoflow/Session/SessionAwareInterface` as inflector to your container definition.

Additionally, you can also use `Neoflow/Session/SessionAwareTrait` as a shorthand implementation of
 `Neoflow/Session/SessionAwareInterface`.

## Usage
Examples how to handle the session:
```php
// Set session name.
$name = 'sid'; // Session name
$session = $session->setName($name);

// Set session cookie.
$session = $session->setCookie([
    // Cookie options
]);

// Start session.
$started = $session->start();

// Get session status.
$status = $session->getStatus();

// Check whether session is started.
$isStarted = $session->isStarted();

// Generate new session id.
$id = $session->generateId();

// Get session cookie.
$cookie = $session->getCookie();

// Get session id.
$id = $session->getId();

// Get session name.
$name = $session->getName();

// Destroy session.
$destroyed = $session->destroy();
```

Examples how to access and manage the values of the session:
```php
// Get session value by key.
$default = null; // Default value, when key doesn't exists
$value = $session->getValue('key', $default);

// Set session value by key.
$overwrite = true; // Set FALSE to prevent overwrite existing value
$session = $session->setValue('key', 'value', $overwrite);

// Check whether session value exists by key.
$valueExists = $session->hasValue('key');
   
// Delete session value by key.
$session->deleteValue('key');

// Count number of session values.
$numberOfValues = $session->countValues();

// Get session values.
$values = $session->getValues();

// Clear session values.
$session = $session->clearValues();

// Replace session values by key. Existing values with similar keys will be overwritten.
$recursive = true; // Set TRUE to enable recursive replacement
$session = $session->replaceValues([
    // Array with key/value pairs
], $recursive);

// Set session values. Existing values will be overwritten.
$session = $session->setValues([
    // Array with key/value pairs
]);
```

## Contributors
* Jonathan Nessier, [Neoflow](https://www.neoflow.ch)

If you would like to see this library develop further, or if you want to support me or show me your appreciation, please
 donate any amount through PayPal. Thank you! :beers:
 
[![Donate](https://img.shields.io/badge/Donate-paypal-blue)](https://www.paypal.me/JonathanNessier)

## License
Licensed under [MIT](LICENSE). 

*Made in Switzerland with :cheese: and :heart:*