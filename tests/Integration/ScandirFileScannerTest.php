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

        $this->assertContains('fileOne.txt', $files[0]);
        $this->assertContains('fileThree.txt', $files[1]);
        $this->assertContains('nested_level/fileTwo.txt', $files[2]);
        $this->assertContains('nested_level/one_more_level/fileOne.txt', $files[3]);
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
