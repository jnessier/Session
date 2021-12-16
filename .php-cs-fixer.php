<?php

// Define excludes
use PhpCsFixer\Finder;

$excludes = [];

// Create finder
$finder = (new Finder())
    ->exclude($excludes)
    ->in([
        './src',
        './tests',
    ]);

// Create config
return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'semicolon_after_instruction' => false,
    ])
    ->setFinder($finder);
