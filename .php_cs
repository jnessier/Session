<?php

PhpCsFixer\Config::create()->setCacheFile(__DIR__.'/.php_cs.cache');

// Define excludes
$excludes = [];

// Create finder
$finder = PhpCsFixer\Finder::create()
    ->exclude($excludes)
    ->in([
        './src',
        './tests',
    ]);

// Create config
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'semicolon_after_instruction' => false,
    ])
    ->setFinder($finder);
