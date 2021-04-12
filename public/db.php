<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) {
	$containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}


$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);


$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$db = $app->getContainer()->get('db');

$query = 'create table if not exists urls(id int not null primary key auto_increment,
url_original varchar(250) not null,
url_curta char(5) not null,
validade timestamp)';
$stmt = $db->prepare($query);
$stmt->execute();

$query = '
CREATE EVENT eventExcluiVencidos
    ON SCHEDULE EVERY 1 DAY
    DO
      DELETE FROM urls WHERE validade < CURRENT_TIMESTAMP();
';
$stmt = $db->prepare($query);
$stmt->execute();