<?php

require_once 'vendor/autoload.php';

session_start();

use Slim\Http\Request;
use Slim\Http\Response;

if (strpos($_SERVER['HTTP_HOST'], "alyamkin.ca") !== false) {
    // hosting on ipd24.ca
    DB::$dbName = 'cp5005_portfolio';
    DB::$user = 'cp5005_portfolio';
    DB::$password = 'Zsu-nB.r8xD2oXbf';
} else { // local computer
    DB::$dbName = 'portfolio';
    DB::$user = 'portfolio';
    DB::$password = 'Zsu-nB.r8xD2oXbf';
    DB::$port = 3333;
}

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true
]];
$app = new \Slim\App($config);

// Fetch DI Container
$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';
// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(dirname(__FILE__) . '/templates', [
        'cache' => dirname(__FILE__) . '/tmplcache',
        'debug' => true, // This line should enable debug mode
    ]);
    //
    $view->getEnvironment()->addGlobal('test1','VALUE');
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    return $view;
};

// for index.html.twig:
$app->get('/', function (Request $request, Response $response, $args) {
    $projects = DB::query("SELECT * FROM projects ORDER BY date DESC");
    return $this->view->render($response, 'index.html.twig', ['projects' => $projects]);
});

// About me
$app->get('/aboutme', function (Request $request, Response $response, $args) {
    return $this->view->render($response, 'aboutme.html.twig');
});
// Education and skills
$app->get('/eduskills', function (Request $request, Response $response, $args) {
    return $this->view->render($response, 'eduskills.html.twig');
});

// load details ajax
$app->get('/ajaxgetdetails/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $details = DB::query("SELECT * FROM details WHERE projectId=%s", $args['id']);
    return $this->view->render($response, 'project-details.html.twig', ['details' => $details]);
});
// load tools ajax
$app->get('/ajaxgettools/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $tools = DB::query("SELECT * FROM tools WHERE projectId=%s", $args['id']);
    return $this->view->render($response, 'project-tools.html.twig', ['tools' => $tools]);
});

    
$app->run();


