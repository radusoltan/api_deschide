<?php

/*
|--------------------------------------------------------------------------
| Run The Shieldon Firewall
|--------------------------------------------------------------------------
|
| Shieldon Firewall will watch all HTTP requests coming to your website.
| Running Shieldon Firewall before initializing Laravel will avoid possible
| conflicts with Laravel's built-in functions.
*/
if (isset($_SERVER['REQUEST_URI'])) {

    // This directory must be writable.
    // We put it in the `storage/shieldon_firewall` directory.
    $storage =  __DIR__ . '/../storage/shieldon_firewall';

    $firewall = new \Shieldon\Firewall\Firewall();

    $firewall->configure($storage);

    // The base url for the control panel.
    $firewall->controlPanel('/firewall/panel/');

    $response = $firewall->run();

    if ($response->getStatusCode() !== 200) {
        $httpResolver = new \Shieldon\Firewall\HttpResolver();
        $httpResolver($response);
    }
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
