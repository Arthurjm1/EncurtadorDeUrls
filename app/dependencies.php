<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },    
    'db' => function ($c){

        $driver = $c->get('dbConfig')['driver'];
        $host = $c->get('dbConfig')['db_host'];
        $dbname = $c->get('dbConfig')['db_name'];
        $user = $c->get('dbConfig')['user'];
        $pass = $c->get('dbConfig')['pass'];

        $db = new \PDO("$driver:host=$host;dbname=$dbname", $user, $pass);

        return $db;
    }
    ]);
};
