<?php declare(strict_types=1);

use PhpCsFixer\Config;
use WyriHaximus\CsFixerConfig\PhpCsFixerConfig;

return (function (): Config
{
    $paths = [
        __DIR__ . DIRECTORY_SEPARATOR . 'src',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests',
    ];

    return PhpCsFixerConfig::create()
        ->setFinder(
            PhpCsFixer\Finder::create()
                ->in($paths)
                ->append($paths)
        )
        ->setUsingCache(false)
        ;
})();
