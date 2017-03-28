<?php
use Sam\Server\Plugins\SecurityPlugin;
use Sam\Server\Plugins\AuthenticationPlugin;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});


$di->set('dispatcher', function() {
    $eventsManager = new EventsManager();

    $eventsManager->attach("dispatch:beforeDispatch", new AuthenticationPlugin);
    $eventsManager->attach("dispatch:beforeDispatch", new SecurityPlugin);

    $dispatcher = new MvcDispatcher();
    $dispatcher->setEventsManager($eventsManager);

    $dispatcher->setDefaultNamespace("Sam\\Server\\Controllers");

    return $dispatcher;
});


/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $connection = new $class([
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ]);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->setShared('router', function() {
    $router = new \Phalcon\Mvc\Router();
    $router->setUriSource($router::URI_SOURCE_SERVER_REQUEST_URI);

    /*
     * add routes
     */

    /*
     * Bank Controller
     */
    $router->addPost("/bank", array(
        "controller" => "bank",
        "action" => "post"
    ));

    $router->addGet("/bank/{id}", array(
        "controller" => "bank",
        "action" => "get"
    ));

    /*
     * User Controller
     */
    $router->addPost("/user", array(
        "controller" => "user",
        "action" => "post"
    ));

    $router->addGet("/user/{loginNr}", array(
        "controller" => "user",
        "action" => "get"
    ));

    /*
     * User Controller
     */
    $router->addPost("/customer", array(
        "controller" => "customer",
        "action" => "post"
    ));

    $router->addGet("/customer/{id}", array(
        "controller" => "customer",
        "action" => "get"
    ));

    /*
     * Employee Controller
     */
    $router->addPost("/employee", array(
        "controller" => "employee",
        "action" => "post"
    ));

    $router->addGet("/employee/{id}", array(
        "controller" => "employee",
        "action" => "get"
    ));

    /*
     * Depot Controller
     */
    $router->addPost("/depot", array(
        "controller" => "depot",
        "action" => "post"
    ));

    $router->addGet("/depot/{id}", array(
        "controller" => "depot",
        "action" => "get"
    ));

    /*
     * OwnedStock Controller
     */
    $router->addPost("/ownedstock", array(
        "controller" => "ownedstock",
        "action" => "post"
    ));

    $router->addGet("/ownedstock/{id}", array(
        "controller" => "ownedstock",
        "action" => "get"
    ));

    /*
     * Stock Controller
     */
    $router->addPost("/stock", array(
        "controller" => "stock",
        "action" => "post"
    ));

    $router->addGet("/stock/{param}/{symbol}", array(
        "controller" => "stock",
        "action" => "get"
    ));

    $router->addGet("/stock/{param}", array(
        "controller" => "stock",
        "action" => "get"
    ));
    /*
     * Transaction Controller
     */
    $router->addPost("/transaction", array(
        "controller" => "transaction",
        "action" => "post"
    ));

    $router->addGet("/transaction/{id}", array(
        "controller" => "transaction",
        "action" => "get"
    ));

    $router->addGet("/transaction/{param}/{amount}/{id}", array(
        "controller" => "transaction",
        "action" => "getParam"
    ));

    return $router;
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
