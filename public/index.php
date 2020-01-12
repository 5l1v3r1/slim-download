<?php
use Slim\App;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Views;
use Ayesh\InstagramDownload\InstagramDownload;

require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$container = new Container($config);
$app = new App($container);
$container = $app->getContainer();

$container['view'] = new Views('../templates/');

$app->get(
    '/',
    function (Request $request, Response $response) {
        $view = $this->get('view');
        $view->render($response, 'home.html');
    }
);
$app->get(
    '/download-instagram',
    function (Request $request, Response $response) {
        $view = $this->get('view');
        return $view->render($response, 'instagram.html');
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
                'instagram-success.php',
                [
                    'type' => $type,
                    'url' => $url
                ]
            );
        } else {
            return $view->render($response, 'instagram-failed.html');
        }
    }
);
$app->get(
    '/download-youtube',
    function (Request $request, Response $response) {
        $this->view->render($response, 'youtube.html');
    }
);
$app->get(
    '/download-facebook',
    function (Request $request, Response $response) {
        $this->view->render($response, 'facebook.html');
    }
);
$app->run();
