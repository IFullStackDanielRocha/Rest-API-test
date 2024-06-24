<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Customer routes
$routes->post('customer/create', 'CustomerController::create');

$routes->get('customers/read', 'CustomerController::index');

$routes->get('customer/read/(:num)', 'CustomerController::getById/$1');

$routes->put('customer/update/(:num)', 'CustomerController::update/$1');

$routes->delete('customer/delete/(:num)', 'CustomerController::del/$1');



// Product 
$routes->post('product/create', 'Product::create');

$routes->get('products/read', 'Product::index');

$routes->get('products/read/(:num)', 'Product::getById/$1');

$routes->put('product/update/(:num)', 'Product::update/$1');

$routes->delete('product/delete/(:num)', 'Product::delete/$1');



// Product Order

$routes->post('order/create', 'OrderController::create');

$routes->get('orders/read', 'OrderController::index');

$routes->get('order/read/(:num)', 'OrderController::getById/$1');

$routes->put('order/update/(:num)', 'OrderController::update/$1');

$routes->delete('order/delete/(:num)', 'OrderController::del/$1');



//Products items

$routes->post('items/create', 'OrderItemsController::create');

$routes->get('items/read', 'OrderItemsController::index');

$routes->get('items/read/(:num)', 'OrderItemsController::getById/$1');

$routes->put('items/update/(:num)', 'OrderItemsController::update/$1');

$routes->delete('items/delete/(:num)', 'OrderItemsController::del/$1');