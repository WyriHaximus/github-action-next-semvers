<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Github\Actions\NextSemVers;

use Version\Exception\InvalidVersionString;
use WyriHaximus\Github\Actions\NextSemVers\Next;
use WyriHaximus\TestUtilities\TestCase;
use function explode;
use function str_replace;
use const PHP_EOL;

/**
 * @internal
 */
final class NextTest extends TestCase
{
    /**
     * @return iterable<string, array<string|bool>>
     */
    public function provideVersions(): iterable
    {
        yield '0.1.0' => [
            '0.1.0',
            '1.0.0',
            '0.2.0',
            '0.1.1',
            false,
        ];

        yield '0.1' => [
            '0.1',
            '1.0.0',
            '0.2.0',
            '0.1.1',
            true,
        ];

        yield '1.0.0' => [
            '1.0.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            false,
        ];

        yield 'v0.1.0' => [
            'v0.1.0',
            '1.0.0',
            '0.2.0',
            '0.1.1',
            false,
        ];

        yield 'v1.0.0' => [
            'v1.0.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            false,
        ];

        yield 'v1.0' => [
            'v1.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            true,
        ];

        yield 'v1' => [
            'v1',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            true,
        ];
    }

    /**
     * @test
     * @dataProvider provideVersions
     */
    public function version(string $version, string $expectedMajor, string $expectedMinor, string $expectedPatch, bool $expectException): void
    {
        $strict                                                    = false;
        $output                                                    = Next::run($version, $strict);
        $output                                                    = str_replace(PHP_EOL, '', $output);
        [$void, $major, $minor, $patch, $majorV, $minorV, $patchV] = explode('::set-output name=', $output);

        self::assertSame('major::' . $expectedMajor, $major, 'major');
        self::assertSame('minor::' . $expectedMinor, $minor, 'minor');
        self::assertSame('patch::' . $expectedPatch, $patch, 'patch');
        self::assertSame('v_major::v' . $expectedMajor, $majorV, 'v_major');
        self::assertSame('v_minor::v' . $expectedMinor, $minorV, 'v_minor');
        self::assertSame('v_patch::v' . $expectedPatch, $patchV, 'v_patch');
    }

    /**
     * @test
     * @dataProvider provideVersions
     */
    public function strict(string $version, string $expectedMajor, string $expectedMinor, string $expectedPatch, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidVersionString::class);
        }

        $strict                                                    = true;
        $output                                                    = Next::run($version, $strict);
        $output                                                    = str_replace(PHP_EOL, '', $output);
        [$void, $major, $minor, $patch, $majorV, $minorV, $patchV] = explode('::set-output name=', $output);

        self::assertSame('major::' . $expectedMajor, $major, 'major');
        self::assertSame('minor::' . $expectedMinor, $minor, 'minor');
        self::assertSame('patch::' . $expectedPatch, $patch, 'patch');
        self::assertSame('v_major::v' . $expectedMajor, $majorV, 'v_major');
        self::assertSame('v_minor::v' . $expectedMinor, $minorV, 'v_minor');
        self::assertSame('v_patch::v' . $expectedPatch, $patchV, 'v_patch');
    }

    /**
     * @test
     */
    public function impossibleVersion(): void
    {
        self::expectException(InvalidVersionString::class);

        Next::run('as$#%$^&*()__dsa.dsasda.sdasdadsadsa', false);
    }
}
