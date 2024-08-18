<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Github\Actions\NextSemVers;

use Version\Exception\InvalidVersionString;
use WyriHaximus\Github\Actions\NextSemVers\Next;
use WyriHaximus\TestUtilities\TestCase;

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
        // Define versions in the following format
        // yield 'INPUT VERSION' => [
        //    INPUT VERSION,
        //    MINIMUM VERSION,
        //    EXPECTED MAJOR VERSION,
        //    EXPECTED MINOR VERSION,
        //    EXPECTED PATCH VERSION,
        //    whether this raises an exception or not in strict mode
        // ];

        yield '0.1.0' => [
            '0.1.0', // INPUT VERSION
            '0.1.0', // MINIMUM VERSION
            '1.0.0', // EXPECTED MAJOR VERSION
            '0.2.0', // EXPECTED MINOR VERSION
            '0.1.1', // EXPECTED PATCH VERSION
            false,   // should raise exception
        ];

        yield '0.1' => [
            '0.1',
            '0.1',
            '1.0.0',
            '0.2.0',
            '0.1.1',
            true,
        ];

        yield '1.0.0' => [
            '1.0.0',
            '1.0.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            false,
        ];

        yield '0.1.0-alpha' => [
            '0.1.0-alpha',
            '0.1.0-alpha',
            '1.0.0',
            '0.2.0',
            '0.1.0',
            false,
        ];

        yield '0.1-alpha' => [
            '0.1-alpha',
            '0.1-alpha',
            '1.0.0',
            '0.2.0',
            '0.1.0',
            true,
        ];

        yield '1.0.0-alpha' => [
            '1.0.0-alpha',
            '1.0.0-alpha',
            '2.0.0',
            '1.1.0',
            '1.0.0',
            false,
        ];

        yield 'v0.1.0' => [
            'v0.1.0',
            'v0.1.0',
            '1.0.0',
            '0.2.0',
            '0.1.1',
            false,
        ];

        yield 'v1.0.0' => [
            'v1.0.0',
            'v1.0.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            false,
        ];

        yield 'v1.0' => [
            'v1.0',
            'v1.0',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            true,
        ];

        yield 'v1' => [
            'v1',
            'v1',
            '2.0.0',
            '1.1.0',
            '1.0.1',
            true,
        ];

        yield 'v1.2.3' => [
            'v1.2.3',
            'v1.0.0',
            '2.0.0',
            '1.3.0',
            '1.2.4',
            false,
        ];

        yield 'v1.2.3' => [
            'v1.2.3',
            'v1.2.4',
            '2.0.0',
            '1.3.0',
            '1.2.5',
            false,
        ];

        yield 'v1.2.3' => [
            'v1.2.3',
            'v1.3.0',
            '2.0.0',
            '1.4.0',
            '1.3.1',
            false,
        ];

        yield 'v1.2.3' => [
            'v1.2.3',
            'v2.0.0',
            '3.0.0',
            '2.1.0',
            '2.0.1',
            false,
        ];
    }

    /**
     * @test
     * @dataProvider provideVersions
     */
    public function version(string $version, string $minimumVersion, string $expectedMajor, string $expectedMinor, string $expectedPatch, bool $expectException): void
    {
        $strict = false;
        $output = Next::run($version, $minimumVersion, $strict);

        self::assertStringContainsString('current=' . $version, $output, 'current');
        self::assertStringContainsString('major=' . $expectedMajor, $output, 'major');
        self::assertStringContainsString('minor=' . $expectedMinor, $output, 'minor');
        self::assertStringContainsString('patch=' . $expectedPatch, $output, 'patch');
        self::assertStringContainsString('v_current=v' . $version, $output, 'v_current');
        self::assertStringContainsString('v_major=v' . $expectedMajor, $output, 'v_major');
        self::assertStringContainsString('v_minor=v' . $expectedMinor, $output, 'v_minor');
        self::assertStringContainsString('v_patch=v' . $expectedPatch, $output, 'v_patch');
    }

    /**
     * @test
     * @dataProvider provideVersions
     */
    public function strict(string $version, string $minimumVersion, string $expectedMajor, string $expectedMinor, string $expectedPatch, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidVersionString::class);
        }

        $strict = true;
        $output = Next::run($version, $minimumVersion, $strict);

        self::assertStringContainsString('current=' . $version, $output, 'current');
        self::assertStringContainsString('major=' . $expectedMajor, $output, 'major');
        self::assertStringContainsString('minor=' . $expectedMinor, $output, 'minor');
        self::assertStringContainsString('patch=' . $expectedPatch, $output, 'patch');
        self::assertStringContainsString('v_current=v' . $version, $output, 'v_current');
        self::assertStringContainsString('v_major=v' . $expectedMajor, $output, 'v_major');
        self::assertStringContainsString('v_minor=v' . $expectedMinor, $output, 'v_minor');
        self::assertStringContainsString('v_patch=v' . $expectedPatch, $output, 'v_patch');
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
