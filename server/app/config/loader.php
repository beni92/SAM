<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->libraryDir
    ]
);

$loader->registerNamespaces(
    [
        "Sam\\Server\\Controllers" => $config->application->controllersDir,
        "Sam\\Server\\Models" => $config->application->modelsDir,
        "Sam\\Server\\Plugins" => $config->application->pluginsDir,
        "Sam\\Server\\Libraries" => $config->application->libraryDir
    ]
);

$loader->register();
