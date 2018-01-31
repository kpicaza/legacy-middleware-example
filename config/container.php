<?php

use Aura\Di\ContainerBuilder;
use InFw\Framework\AuraConfig;

// Load configuration
$config = require __DIR__ . '/../config/config.php';
$config['dependencies']['invokables'] = array_merge(
    $config['dependencies']['invokables'],
    $config['console']['invokables']
);
$config['dependencies']['factories'] = array_merge(
    $config['dependencies']['factories'],
    $config['console']['factories']
);

// Build container
$builder = new ContainerBuilder();

return $builder->newConfiguredInstance([
    new AuraConfig(is_array($config) ? $config : []),
], $builder::AUTO_RESOLVE);
