<?php

$main = "../apps/routes/main";

$routes = [];
foreach (glob("{$main}/*.php") as $filename) {
    $route = require($filename);
    $routes = array_merge($routes, $route);
}

return $routes;