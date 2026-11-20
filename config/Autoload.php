<?php
spl_autoload_register(function ($class) {
    $base = dirname(__DIR__);
    $paths = ['/config/', '/controllers/', '/models/'];
    foreach ($paths as $path) {
        $file = $base . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
