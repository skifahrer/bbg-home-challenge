<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

// core
$container
    ->register('mysql_connection', \App\Core\Database\MariaDatabaseConnection::class);

// Repository
$container
    ->register('category_repo', App\Repository\Maria\MariaProductCategoryRepository::class)
    ->addArgument(new Reference('mysql_connection'));

$container
    ->register('product_repo', App\Repository\Maria\MariaProductRepository::class)
    ->addArgument(new Reference('mysql_connection'));

// Service
$container
    ->register('product_service', App\Service\ProductService::class)
    ->addArgument(new Reference('product_repo'))
    ->addArgument(new Reference('category_repo'));

// Controllers
$container
    ->register('product_api_controller', App\Controller\Api\ProductApiController::class)
    ->addArgument(new Reference('product_service'));

return $container;
