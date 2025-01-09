<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\HotReloader;

use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\Attributes\Group;

/**
 * @internal
 */
#[Group('Others')]
final class DirectoryHasherTest extends CIUnitTestCase
{
    private DirectoryHasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = new DirectoryHasher();
    }

    public function testHashApp(): void
    {
        $results = $this->hasher->hashApp();

        $this->assertIsArray($results);
        $this->assertArrayHasKey('app', $results);
    }

    public function testHashDirectoryInvalid(): void
    {
        $this->expectException(FrameworkException::class);
        $this->expectExceptionMessage('Directory does not exist: "' . APPPATH . 'Foo"');

        $this->hasher->hashDirectory(APPPATH . 'Foo');
    }

    public function testUniqueHashes(): void
    {
        $hash1 = $this->hasher->hashDirectory(APPPATH);
        $hash2 = $this->hasher->hashDirectory(SYSTEMPATH);

        $this->assertNotSame($hash1, $hash2);
    }

    public function testRepeatableHashes(): void
    {
        $hash1 = $this->hasher->hashDirectory(APPPATH);
        $hash2 = $this->hasher->hashDirectory(APPPATH);

        $this->assertSame($hash1, $hash2);
    }

    public function testHash(): void
    {
        $expected = md5(implode('', $this->hasher->hashApp()));

        $this->assertSame($expected, $this->hasher->hash());
    }
}
