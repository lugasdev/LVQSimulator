<?php
date_default_timezone_set("UTC");
define('APP_DIR', __DIR__ . '/');

include "vendor/autoload.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$app = new \Slim\App();

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new Lugas\LvqSimulator\Twig;

    return $view->view();
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                        ->write($exception->getMessage());
    };
};


foreach (glob(APP_DIR . "routes/*.php") as $filename) {
    include $filename;
}

$app->run();

function pr($param) {
    echo "<pre>";
    print_r($param);
    echo "</pre>";
}
