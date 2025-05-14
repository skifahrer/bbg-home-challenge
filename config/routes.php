<?php
declare(strict_types=1);

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('category_list', new Route(
    '/api/categories',
    ['_controller' => ['product_api_controller', 'getProductCategoryList']],
    [],
    [],
    '',
    [],
    ['GET']
));

$routes->add('product_list', new Route(
    '/api/products',
    ['_controller' => ['product_api_controller', 'getList']],
    [],
    [],
    '',
    [],
    ['GET']
));

$routes->add('product_get', new Route(
    '/api/products/{id}',
    ['_controller' => ['product_api_controller', 'getDetail']],
    [],
    [],
    '',
    [],
    ['GET']
));

return $routes;
