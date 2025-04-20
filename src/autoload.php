<?php
require_once "config.php";

function my_autoloader($class)
{
    // Convert namespace to file path
    $class = str_replace('Johnl\\GlassesShop\\', '', $class);
    $class = str_replace('\\', '/', $class);

    $path = __DIR__ . '/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        throw new Exception("Class " . $class . " not found");
    }
}

spl_autoload_register('my_autoloader');
