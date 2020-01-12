<?php
use Slim\App;
use Slim\Container;
use Slim\HttpCache\CacheProvider;
use Slim\HttpCache\Cache;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Views;
use Ayesh\InstagramDownload\InstagramDownload;
require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'routerCacheFile' => '../cache/cache.php'
    ]
];

$container = new Container($config);
$app = new App($container);
$container = $app->getContainer();
// Add container to the application
$container['view'] = new Views('../templates/');
$container['cache'] = new CacheProvider();
// Add middleware to the application
$app->add(new Cache('public', 86400));

$app->get(
    '/',
    function (Request $request, Response $response) {
        $view = $this->get('view');
        $view->render($response, 'home.html');
    }
);
$app->post(
    '/download-instagram',
    function (Request $request, Response $response) {
        $view = $this->get('view');
        $args = $request->getParsedBody();
        if (isset($args['link'])) {
            $ig = new InstagramDownload($args['link']);
            $url = $ig->getDownloadUrl();
            $type = $ig->getType();
            return $view->render(
                $response,
                'success.php',
                [
                    'type' => $type,
                    'url' => $url
                ]
            );
        } else {
            return $view->render($response, 'failed.html');
        }
    }
);
$app->get(
    '/download-{type}',
    function (Request $request, Response $response, $args) {
        $this->view->render($response, $args['type'] . '.html');
    }
);
$app->run();
