<?php declare(strict_types=1);

namespace WyriHaximus\Github\Actions\NextSemVers;

use Version\Exception\InvalidVersionStringException;
use Version\Version;
use function count;
use function explode;
use const PHP_EOL;
use const WyriHaximus\Constants\Numeric\ONE;
use const WyriHaximus\Constants\Numeric\TWO;

final class Next
{
    public static function run(string $versionString, bool $strict): string
    {
        try {
            $version = Version::fromString($versionString);
        } catch (InvalidVersionStringException $invalidVersionException) {
            if ($strict === true) {
                throw $invalidVersionException;
            }

            if (count(explode('.', $versionString)) === ONE + TWO) {
                throw $invalidVersionException;
            }

            return self::run($versionString . '.0', $strict);
        }

        // Raw versions
        $output  = '::set-output name=major::' . $version->incrementMajor() . PHP_EOL;
        $output .= '::set-output name=minor::' . $version->incrementMinor() . PHP_EOL;
        $output .= '::set-output name=patch::' . $version->incrementPatch() . PHP_EOL;

        // v prefixed versions
        $output .= '::set-output name=v_major::v' . $version->incrementMajor() . PHP_EOL;
        $output .= '::set-output name=v_minor::v' . $version->incrementMinor() . PHP_EOL;
        $output .= '::set-output name=v_patch::v' . $version->incrementPatch() . PHP_EOL;

        return $output;
    }
}
