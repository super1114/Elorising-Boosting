<?php

$container["TestController"] = function($container) {
  return new \App\Controllers\TestController($container);
};

$container["HomeController"] = function($container) {
  return new \App\Controllers\HomeController($container);
};

$container["AdminController"] = function($container) {
  return new \App\Controllers\AdminController($container);
};

$container["BoosterController"] = function($container) {
  return new \App\Controllers\BoosterController($container);
};

$container["AuthController"] = function($container) {
  return new \App\Controllers\AuthController($container);
};

$container["OrderController"] = function($container) {
  return new \App\Controllers\OrderController($container);
};

$app->get('/test', 'TestController:index')->setName('test');

$app->get('/',                'HomeController:index')->setName('home');
$app->get('/solo',            'HomeController:solo')->setName('solo');
$app->get('/duo',             'HomeController:duo')->setName('duo');
$app->get('/placements',      'HomeController:placements')->setName('placements');
$app->get('/wins',            'HomeController:wins')->setName('wins');
$app->get('/deals',           'HomeController:deals')->setName('deals');
$app->get('/reviews',         'HomeController:reviews')->setName('reviews');
$app->get('/contact',         'HomeController:contact')->setName('contact');
$app->post('/addReview',      'HomeController:addReview')->setName('addReview');
$app->post('/mailContact',    'HomeController:mailContact')->setName('mailContact');
$app->post('/forgotPassword', 'HomeController:forgotPassword')->setName('forgotPassword');
$app->get('/changePassword',  'HomeController:changePassword')->setName('changePassword');
$app->post('/resetPassword',  'HomeController:resetPassword')->setName('resetPassword');


// User Orderpage
$app->get('/user/order',  'HomeController:order')->setName('user-order');

// Order and Checkout
$app->post('/order',          'OrderController:order')->setName('order');
$app->post('/order-update',   'OrderController:updateOrder')->setName('order-update');
$app->post('/referralCheck',  'OrderController:referralCheck')->setName('referral-check');

// Auth
$app->post('/login',       'AuthController:login')->setName('login');
$app->post('/register',    'AuthController:register')->setName('register');
$app->get('/logout',       'AuthController:logout')->setName('logout');

// Admin
$app->get('/admin-panel',                       'AdminController:index')->setName('admin');
$app->get('/admin-panel/orders/{order_state}',  'AdminController:orders');
$app->get('/admin-panel/boosters',              'AdminController:boosters');
$app->get('/admin-panel/referrals',             'AdminController:referrals');
$app->get('/admin-panel/checkouts',             'AdminController:checkouts');
$app->post('/admin-panel/addReferral',          'AdminController:addReferral');
$app->post('/admin-panel/addBooster',           'AdminController:addBooster');
$app->post('/admin-panel/updateApi',            'AdminController:updateApi');
$app->post('/admin-panel/markAsPaid',           'AdminController:markAsPaid');

// Booster
$app->get('/booster-panel',                 'BoosterController:index')->setName('booster');
$app->get('/booster-panel/orders',          'BoosterController:getOrders');
$app->get('/booster-panel/order',           'BoosterController:getOrder');
$app->get('/booster-panel/order-history',   'BoosterController:orderHistory');
$app->post('/booster-panel/takeOrder',      'BoosterController:takeOrder');
$app->post('/booster-panel/cancelOrder',    'BoosterController:cancelOrder');
$app->post('/booster-panel/updateOrder',    'BoosterController:updateOrder');
$app->post('/booster-panel/completeOrder',  'BoosterController:completeOrder');
$app->post('/booster-panel/updateBooster',  'BoosterController:updateBooster');
$app->post('/booster-panel/checkout',       'BoosterController:checkout');
