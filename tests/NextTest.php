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

        yield 'v0.1.0' => [
            'v0.1.0',
            '1.0.0',
            '0.2.0',
            '0.1.1',
        ];

        yield 'v1.0.0' => [
            'v1.0.0',
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
    public function version(string $version, string $expectedMajor, string $expectedMinor, string $expectedPatch): void
    {
        $output = Next::run($version);
        $output = \str_replace(\PHP_EOL, '', $output);
        [$_, $major, $minor, $patch, $majorV, $minorV, $patchV] = \explode('::set-output name=', $output);

        self::assertSame('major::' . $expectedMajor, $major, 'major');
        self::assertSame('minor::' . $expectedMinor, $minor, 'minor');
        self::assertSame('patch::' . $expectedPatch, $patch, 'patch');
        self::assertSame('v_major::v' . $expectedMajor, $majorV, 'v_major');
        self::assertSame('v_minor::v' . $expectedMinor, $minorV, 'v_minor');
        self::assertSame('v_patch::v' . $expectedPatch, $patchV, 'v_patch');
    }
}
