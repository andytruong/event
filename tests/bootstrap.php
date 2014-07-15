<?php

$locations[] = __DIR__ . "/../vendor/autoload.php";
$locations[] = __DIR__ . "/../../../autoload.php";

foreach ($locations as $location) {
    if (is_file($location)) {
        $loader = require $location;
        $loader->addPsr4('AndyTruong\\Event\\TestCases\\', __DIR__ . '/event');
        $loader->addPsr4('AndyTruong\\Event\\Fixtures\\', __DIR__ . '/fixtures');
        $loader->addPsr4('AndyTruong\\Event\\', __DIR__ . '/../src');
        break;
    }
}
