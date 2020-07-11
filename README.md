# Session
[![Build Status](https://travis-ci.org/Neoflow/Session.svg?branch=master&service=github)](https://travis-ci.org/Neoflow/Session)
[![Coverage Status](https://coveralls.io/repos/github/Neoflow/Session/badge.svg?branch=master&service=github)](https://coveralls.io/github/Neoflow/Session?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
 
Session middleware with flash message support for Slim 4 and similar [PSR-15](https://www.php-fig.org/psr/psr-15/)
 compliant frameworks and apps. 

## Requirement
* PHP >= 7.4

## Installation
You have 2 options to install this library.

Via Composer:
```bash
composer require neoflow/session
```

Or manually add this line to the `require` block in your `composer.json`:
```json
"neoflow/session": "~0.1"
```
## Manual
The following instructions based on Slim 4, but should be adaptable any PSR-15 compliant frameworks and apps.

### Middleware
Add the `Neoflow\Session\Middleware\SessionMiddleware` to the middleware dispatcher. 
The middleware handles your session configuration and starts the session, after each request. 

```php
$app->add(new Neoflow\Session\Middleware\SessionMiddleware([
    // your custom session options
]));
```
The following options are supported:

| Key | Type | Description | Default |
|---|---|---|---|
| ```name``` | string | Name of the session cookie. | SID |
| ```lifetime``` | int | Session lifetime in seconds. | 3600 |
| ```autoRefresh``` | bool | Refresh of session lifetime after each request. | true |
| ```withAttribute``` | bool | Helper class as request attribute. | false |
| ```sessionKey``` | string | Key for session data. | _sessionData |
| ```flashKey``` | string | Key for flash messages.  | _flashMessages |

### Helper
Use `Neoflow\Session\Session` and `Neoflow\Session\Flash` as helper, to get extended functionality for the
 session handling, and the ability to manage and get access to session data and flash messages.
  
#### Initialization
You have 2 options to set up and initialize the helper. 

Inject `Neoflow\Session\Flash`, add the `Neoflow\Session\Session` to the container...
```php
$container = new DI\Container();
$container
    ->set('session', function () use ($container) {
        $flash = new Neoflow\Session\Flash();
        return new Neoflow\Session\Session($flash);
    });
```
...and use the helper as dependency:
```php
$app->get('/', function (Request $request, Response $response) {
    $session = $this->get('session');

    // Your custom code

    return $response;
});
```
Or enable option `withAttribute` for the `Neoflow\Session\Middleware\SessionMiddleware`...
```php
$app->add(new Neoflow\Session\Middleware\SessionMiddleware([
    'withAttribute' => true
]));
```
...and get the helper as request attribute:
```php
$app->get('/', function (Psr\Http\Message\RequestInterface $request, Psr\Http\Message\ResponseInterface $response) {
    $session = $request->getAttribute('session');

    // Your custom code

    return $response;
});
```

**Important:** 
Don't use both options simultaneously. 
It's important to set up the helper only once. 
Otherwise, it can cause data conflicts with the flash messages.
 
#### Usage
Examples how to handle the session:
```php
// Get session id
$id = $session->getId();

// Generate new session id
$deleteOldSession = false;
$session = $session->generateId($deleteOldSession); 

// Get session status
$status = $session->getStatus();

// Destroy session
$destroyed = $session->destroy();

// Get session name
$name = $session->getName();
```

Examples how to manage the session data:
```php
// Get session value by key, or default value when key doesn't exists 
$value = $session->get('key', 'default');

// Check whether session value exists by key
$exists = $session->exists('key');

// Set key and value of session data
$session = $session->set('key', 'value');

// Delete session value by key
$deleted = $session->delete('key');

// Merge multiple keys and values of session data
$flash = $session->merge([
    'key' => 'value',
    'key2' => 'value2'
]);

// Get session data as array
$array = $session->toArray();

// Apply a callback with arguments to the session data
$result = $session->apply(function (Neoflow\Session\Session $session, string $arg1, string $arg2) {
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

Examples how to get read-only access to the flash messages, set in previous request:
```php
// Check whether flash message exists by key
$exists = $session->flash()->exists('key');

// Get flash message by key, or default value when the key doesn't exists 
$value = $session->flash()->get('key', 'default');

// Get flash messages as array (set in previous request)
$array = $session->flash()->toArray();

// Iterate trough the flash messages
$result = $session->flash()->each(function (string $key, $value) {
    // Your custom code
});
```

Examples how to manage the new flash messages, set for the next request:
```php
// Set key and value of new flash message
$flash = $session->flash()->setNew('key', 'value');

// Check whether new flash message exists by key
$exists = $session->flash()->existsNew('key');

// Get new flash message by key, or default value when the key doesn't exists
$value = $session->flash()->getNew('key', 'default');

// Delete new flash message by key
$deleted = $session->flash()->deleteNew('key');

// Merge multiple keys and values of flash messages
$flash = $session->flash()->mergeNew([
    'key' => 'value',
    'key2' => 'value2'
]);

// Get new flash messages as array
$array = $session->flash()->toArrayNew();

// Iterate trough the new flash messages
$result = $session->flash()->eachNew(function (string $key, $value) {
    // Your custom code
});
```

Examples how to manage both types of flash messages:
```php
// Apply a callback with arguments to the flash helper
$result = $session->flash()->apply(function (Neoflow\Session\Flash $flash, string $arg, string $arg2) {
   // Your custom code
}, [
   'arg',
   'arg2'
]);
```

## Contributors
* Jonathan Nessier, [Neoflow](https://www.neoflow.ch)

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