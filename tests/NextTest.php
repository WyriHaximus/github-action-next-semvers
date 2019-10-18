<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Github\Actions\NextSemVers;

use WyriHaximus\Github\Actions\NextSemVers\Next;
use WyriHaximus\TestUtilities\TestCase;

/**
 * @internal
 */
final class NextTest extends TestCase
{
    public function provideVersions(): iterable
    {
        yield '0.1.0' => [
            '0.1.0',
            '1.0.0',
            '0.2.0',
            '0.1.1',
        ];

        yield '1.0.0' => [
            '1.0.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideVersions
     */
    public function version(string $version, string $expectedMayor, string $expectedMinor, string $expectedPatch): void
    {
        $output = Next::run($version);
        $output = \str_replace(\PHP_EOL, '', $output);
        [$_, $mayor, $minor, $patch] = \explode('::set-output name=', $output);

        self::assertSame('mayor::' . $expectedMayor, $mayor, 'mayor');
        self::assertSame('minor::' . $expectedMinor, $minor, 'minor');
        self::assertSame('patch::' . $expectedPatch, $patch, 'patch');
    }
}
