<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use App\Domain\Databox\DataboxPush;
use App\Domain\Facebook\FacebookMetrics;
use App\Domain\Google\GoogleMetrics;


return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    LoggerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);
        $handler = new StreamHandler($settings['path'], $settings['level']);
        $logger->pushHandler($handler);

        return $logger;
    },
            
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },
  
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details'], 
            $container->get(LoggerInterface::class)
        );
    },
            
    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },
    JsonMapper::class =>function (ContainerInterface $container) {
        $mapper = new JsonMapper();
        $mapper->setLogger($container->get(LoggerInterface::class));
        return $mapper;
    }, 
    DataboxPush::class =>function (ContainerInterface $container) {
        return new DataboxPush($container->get(LoggerInterface::class), $container->get(JsonMapper::class));
    }, 
    FacebookMetrics::class =>function (ContainerInterface $container) {
        return new FacebookMetrics($container->get(LoggerInterface::class), $container->get(JsonMapper::class));
    }, 
    GoogleMetrics::class =>function (ContainerInterface $container) {
        return new GoogleMetrics($container->get(LoggerInterface::class), $container->get(JsonMapper::class), $container->get('settings'));
    }

            
];
