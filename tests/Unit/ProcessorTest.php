<?php
/*
 * This file is part of smytsyk-dev/freque.
 *
 * (c) Stas Mytsyk <denielm10@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Freque\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Freque\File;
use Freque\Processor;
use Freque\Utils\ScandirFileScanner;

class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private $subject;

    /**
     * @var ScandirFileScanner
     */
    private $fileScanner;

    protected function setUp()
    {
        $this->fileScanner = $this->prophesize(ScandirFileScanner::class);

        $this->subject = new Processor($this->fileScanner->reveal());
    }

    /**
     * @test
     */
    public function itProcessesAllFilesByPath()
    {
        $this->fileScanner->loadByPath('testrepo/')->willReturn(
            ['firstFile.txt',
             'secondFile.txt',
             'nested_level/thirdFile.txt']
        );

        $result = $this->subject->loadByPath('testrepo/');

        $this->assertEquals(
            [new File('firstFile.txt'),
             new File('secondFile.txt'),
             new File('nested_level/thirdFile.txt')],
            $result
        );
    }
}