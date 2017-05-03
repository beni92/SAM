<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir
    ]
);

$loader->registerNamespaces(
    [
        "Sam\\Client\\Controllers" => $config->application->controllersDir,
        "Sam\\Client\\Models" => $config->application->modelsDir,
        "Sam\\Client\\Plugins" => $config->application->pluginsDir
    ]
);

$loader->register();
