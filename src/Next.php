<?php

declare(strict_types=1);

namespace WyriHaximus\Github\Actions\NextSemVers;

use Version\Exception\InvalidVersionString;
use Version\Version;

use function count;
use function explode;
use function strpos;

use const WyriHaximus\Constants\Numeric\ONE;
use const WyriHaximus\Constants\Numeric\TWO;

final class Next
{
    private const PRE_RELEASE_CHUNK_COUNT = 2;

    public static function run(string $versionString, bool $strict): string
    {
        try {
            $version = Version::fromString($versionString);
        } catch (InvalidVersionString $invalidVersionException) {
            if ($strict === true) {
                throw $invalidVersionException;
            }

            if (count(explode('.', $versionString)) === ONE + TWO) {
                throw $invalidVersionException;
            }

            // split versionString by '-' (in case it is a pre-release)
            if (strpos($versionString, '-') !== false) {
                [$versionString, $preRelease] = explode('-', $versionString, self::PRE_RELEASE_CHUNK_COUNT);
                $versionString               .= '.0-' . $preRelease;
            } else {
                $versionString .= '.0';
            }

            return self::run($versionString, $strict);
        }

        $wasPreRelease = false;

        // if current version is a pre-release
        if ($version->isPreRelease()) {
            // get current version by removing anything else (e.g., pre-release, build-id, ...)
            $version       = Version::from($version->getMajor(), $version->getMinor(), $version->getPatch());
            $wasPreRelease = true;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////
        // Raw versions
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $output  = 'major=' . $version->incrementMajor() . "\n";
        $output .= 'minor=' . $version->incrementMinor() . "\n";

        ///////////////////////////////////////////////////////////////////////////////////////////////
        // v prefixed versions
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $output .= 'v_major=v' . $version->incrementMajor() . "\n";
        $output .= 'v_minor=v' . $version->incrementMinor() . "\n";

        // check if current version is a pre-release
        if ($wasPreRelease) {
            // use current version (without pre-release)
            $output .= 'patch=' . $version . "\n";
            // v prefixed versions
            $output .= 'v_patch=v' . $version . "\n";
        } else {
            // increment major/minor/patch version
            $output .= 'patch=' . $version->incrementPatch() . "\n";
            // v prefixed versions
            $output .= 'v_patch=v' . $version->incrementPatch() . "\n";
        }

        return $output;
    }
}
