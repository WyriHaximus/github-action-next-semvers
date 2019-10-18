<?php

use Version\Version;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

const VERSION = 'INPUT_VERSION';

(function () {
    $version = Version::fromString(getenv(VERSION));
    echo PHP_EOL, '::set-output name=mayor::' . (string)$version->incrementMajor(), PHP_EOL;
    echo PHP_EOL, '::set-output name=minor::' . (string)$version->incrementMinor(), PHP_EOL;
    echo PHP_EOL, '::set-output name=patch::' . (string)$version->incrementPatch(), PHP_EOL;
})();
