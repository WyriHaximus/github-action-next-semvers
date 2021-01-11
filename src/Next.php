<?php

declare(strict_types=1);

namespace WyriHaximus\Github\Actions\NextSemVers;

use Version\Exception\InvalidVersionString;
use Version\Version;

use function count;
use function explode;
use function strpos;

use const PHP_EOL;
use const WyriHaximus\Constants\Numeric\ONE;
use const WyriHaximus\Constants\Numeric\TWO;

final class Next
{
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
            if (strpos($versionString, '-') >= 1) {
                $pieces         = explode('-', $versionString, 2);
                $versionString  = $pieces[0];
                $preRelease     = $pieces[1];
                $versionString .= '.0-' . $preRelease;
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
        $output  = '::set-output name=major::' . $version->incrementMajor() . PHP_EOL;
        $output .= '::set-output name=minor::' . $version->incrementMinor() . PHP_EOL;

        // check if current version is a pre-release
        if ($wasPreRelease) {
            // use current version (without pre-release)
            $output .= '::set-output name=patch::' . $version . PHP_EOL;
        } else {
            // increment major/minor/patch version
            $output .= '::set-output name=patch::' . $version->incrementPatch() . PHP_EOL;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////
        // v prefixed versions
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $output .= '::set-output name=v_major::v' . $version->incrementMajor() . PHP_EOL;
        $output .= '::set-output name=v_minor::v' . $version->incrementMinor() . PHP_EOL;

        // check if current version is a pre-release
        if ($wasPreRelease) {
            // use current version (without pre-release)
            // v prefixed versions
            $output .= '::set-output name=v_patch::v' . $version . PHP_EOL;
        } else {
            // increment major/minor/patch version
            $output .= '::set-output name=v_patch::v' . $version->incrementPatch() . PHP_EOL;
        }

        return $output;
    }
}
