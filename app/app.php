<?php

  session_start();

  require __DIR__."/../vendor/autoload.php";

  $app = new \Slim\App([
    'settings' => [
      'displayErrorDetails' => true,
      'determineRouteBeforeAppMiddleware' => true,
      'displayErrorDetails' => true,
      'addContentLengthHeader' => false,

      'db' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'boosting_app',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'latin1',
        'collation' => 'latin1_swedish_ci',
        'prefix'    => '',
      ]
    ],
  ]);

  $container = $app->getContainer();

  // Eloquent setup
  $capsule = new \Illuminate\Database\Capsule\Manager;
  $capsule->addConnection($container["settings"]["db"]);
  $capsule->setAsGlobal();
  $capsule->bootEloquent();

  $container["db"] = function($container) use ($capsule) {
    return $capsule;
  };

  $container["view"] = function($container) {

    $view = new \Slim\Views\Twig(__DIR__."/views");

    $view->addExtension(new \Slim\Views\TwigExtension(
      $container->router,
      $container->request->getUri()
    ));

    return $view;

  };

  require __DIR__."/routes.php";
