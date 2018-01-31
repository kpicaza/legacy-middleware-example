<?php

// Delegate static file requests back to the PHP built-in webserver
use Dotenv\Dotenv;

if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
call_user_func(function () {

    if (class_exists(Dotenv::class)) {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();
    }

    /** @var \Interop\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Zend\Expressive\Application $app */
    $app = $container->get(\Zend\Expressive\Application::class);

    // Import programmatic/declarative middleware pipeline and routing
    // configuration statements
    require 'routes/pipeline.php';
    require 'routes/routes.php';

    $app->run();
});
