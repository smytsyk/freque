<?php
/*
 * This file is part of smytsyk-dev/freque.
 *
 * (c) Stas Mytsyk <denielm10@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Freque\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Freque\Utils\ScandirFileScanner;

class ScandirFileScannerTest extends TestCase
{
    /**
     * @var ScandirFileScanner
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new ScandirFileScanner();
    }

    /**
     * @test
     */
    public function itSkipsNonFiles()
    {
        $files = $this->subject->loadByPath(sprintf(
            '%s/testrepo/',
            __DIR__
        ));

        $this->assertSame(
            [
                'fileOne.txt',
                'fileThree.txt',
                'nested_level/fileTwo.txt',
            ],
            $files
        );
    }

    /**
     * @test
     * @expectedException \Freque\Utils\DirectoryPathNotFound
     */
    public function itThrowsExceptionWhenLoadsByNonExistingDirectory()
    {
        $this->subject->loadByPath('nonExistingDirectory');
    }
}
