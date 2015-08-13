<?php

// define routes

$routes = array(
    array(
        "pattern" => "features",
        "controller" => "home",
        "action" => "features"
    ),
    array(
        "pattern" => "home",
        "controller" => "home",
        "action" => "index"
    ),
    array(
        "pattern" => "about",
        "controller" => "home",
        "action" => "about"
    ),
    array(
        "pattern" => "terms",
        "controller" => "home",
        "action" => "terms"
    ),
    array(
        "pattern" => "privacy",
        "controller" => "home",
        "action" => "privacy"
    ),
    array(
        "pattern" => "contact",
        "controller" => "home",
        "action" => "contact"
    ),
    array(
        "pattern" => "faq",
        "controller" => "home",
        "action" => "faq"
    )
);

// add defined routes
foreach ($routes as $route) {
    $router->addRoute(new Framework\Router\Route\Simple($route));
}

// unset globals
unset($routes);
