<?php

require_once '../vendor/autoload.php';
session_start();

$router = new AltoRouter();


if (array_key_exists('BASE_URI', $_SERVER)) {
 
    $router->setBasePath($_SERVER['BASE_URI']);
    
}

else {
   
    $_SERVER['BASE_URI'] = '/';
}

$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-home'
);
$router->map(
    'GET',
    '/users/[a:string]',
    [
        'method' => 'listAllUsers',
        'controller' => '\App\Controllers\ContactController'
    ],
    'contact-listAllUsers'
);
$router->map(
    'POST',
    '/uploadImage',
    [
        'method' => 'uploadImage',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-uploadImage'
);
$router->map(
    'GET',
    '/discussions',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\ChatController'
    ],
    'chat-list',
    
);
$router->map(
    'GET',
    '/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\ChatController'
    ],
    'chat-delete',
    
);
$router->map(
    'GET',
    '/contacts',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\ContactController'
    ],
    'contact-list',
    
);
$router->map(
    'GET',
    '/contacts/profile/[i:id]',
    [
        'method' => 'profile',
        'controller' => '\App\Controllers\ContactController'
    ],
    'contact-profile',
    
);
$router->map(
    'GET',
    '/contacts/befriend/[i:id]',
    [
        'method' => 'befriend',
        'controller' => '\App\Controllers\ContactController'
    ],
    'contact-befriend',
    
);
$router->map(
    'GET',
    '/contact/supprimer/[i:id]',
    [
        'method' => 'unfriend',
        'controller' => '\App\Controllers\ContactController'
    ],
    'contact-unfriend',
    
);
$router->map(
    'GET',
    '/chat/[i:id]',
    [
        'method' => 'getChat',
        'controller' => '\App\Controllers\ChatController'
    ],
    'chat-getChat',
    
);
$router->map(
    'GET',
    '/markAsRead/[i:chatId]',
    [
        'method' => 'markAsRead',
        'controller' => '\App\Controllers\ChatController'
    ],
    'chat-markAsRead',
    
);
$router->map(
    'POST',
    '/chat/[i:id]',
    [
        'method' => 'sendMessage',
        'controller' => '\App\Controllers\ChatController'
    ],
    'chat-sendMessage',
    
);
$router->map(
    'GET',
    '/compte',
    [
        'method' => 'login',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-login',
    
);

$router->map(
    'GET',
    '/compte/deconnexion',
    [
        'method' => 'logout',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-logout',
    
);
$router->map(
    'POST',
    '/compte',
    [
        'method' => 'loginPost',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-loginPost',
    
);
$router->map(
    'GET',
    '/compte/creation',
    [
        'method' => 'create',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-create',
    
);
$router->map(
    'POST',
    '/compte/creation',
    [
        'method' => 'save',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-save',
    
);
$router->map(
    'GET',
    '/compte/details',
    [
        'method' => 'detail',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-detail',
    
);
$router->map(
    'POST',
    '/compte/details',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\AccountController'
    ],
    'account-update',
    
);

$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();