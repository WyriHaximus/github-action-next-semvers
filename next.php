<?php declare(strict_types=1);

use WyriHaximus\Github\Actions\NextSemVers\Next;

require __DIR__ . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';

exit((function (): int {
    $versionString = \getenv('INPUT_VERSION');

    if ($versionString === false) {
        return 1;
    }

    file_put_contents(getenv('GITHUB_OUTPUT'), Next::run($versionString, \getenv('INPUT_STRICT') === 'false' ? false : true), FILE_APPEND);

    return 0;
})());
