<?php declare(strict_types=1);

namespace WyriHaximus\Github\Actions\NextSemVers;

use Version\Version;

final class Next
{
    public static function run(string $versionString): string
    {
        $version = Version::fromString($versionString);

        // Raw versions
        $output  = '::set-output name=mayor::' . $version->incrementMajor() . \PHP_EOL;
        $output .= '::set-output name=minor::' . $version->incrementMinor() . \PHP_EOL;
        $output .= '::set-output name=patch::' . $version->incrementPatch() . \PHP_EOL;

        // v prefixed versions
        $output .= '::set-output name=v_mayor::v' . $version->incrementMajor() . \PHP_EOL;
        $output .= '::set-output name=v_minor::v' . $version->incrementMinor() . \PHP_EOL;
        $output .= '::set-output name=v_patch::v' . $version->incrementPatch() . \PHP_EOL;

        return $output;
    }
}
