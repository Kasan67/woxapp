<?php

use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Cache\Frontend\Data as FrontData;

try {
    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/models/'
        )
    )->register();

    // Create a DI
    $di = new FactoryDefault();

    // Set the database service
    $di['db'] = function() {
        return new DbAdapter(array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "7748099",
            "dbname"   => "woxapp"
        ));
    };

    // Setting up the view component
    $di['view'] = function() {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    };

    // Setup a base URI so that all generated URIs include the "woxapp" folder
    $di['url'] = function() {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    };

    // Setup the tag helpers
    $di['tag'] = function() {
        return new Tag();
    };

    // Cache data for 2 days
    $frontCache = new FrontData([
        'lifetime' => 172800
    ]);

    // Create the Cache setting redis connection options
    $cache = new Redis($frontCache, [
        'host' => 'localhost',
        'port' => 6379,
        'persistent' => false,
        'index' => 0,
    ]);

    // Сессии запустятся один раз, при первом обращении к объекту
    $di->setShared(
        "session",
        function () {
            $session = new Session();

            $session->start();

            return $session;
        }
    );

    // Handle the request
    $application = new Application($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}