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

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Freque\File;
use Freque\FileHistory;
use Freque\JsonFormatter;
use Freque\Reporter;

class ReporterTest extends TestCase
{
    /**
     * @var Reporter
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new Reporter(new JsonFormatter());
    }

    /**
     * @test
     */
    public function itGeneratesReportWithNumOfChangesPerFileAsJson()
    {
        $fileOne = new File('sub_directory/something.txt');
        $fileOne->addHistory(
            new FileHistory('a', 'b',
                (new DateTimeImmutable())->setTimestamp(1114123410)) // 22.04.2005 - 00:43:30
        );
        $fileOne->addHistory(
            new FileHistory('a', 'b',
                (new DateTimeImmutable())->setTimestamp(1234423440)) // 12.02.2009 - 08:24:00
        );

        $fileTwo = new File('file.txt');

        $fileTwo->addHistory(
            new FileHistory('a', 'b',
                (new DateTimeImmutable())->setTimestamp(1394123420)) // 06.03.2014 - 17:30:20
        );
        $fileTwo->addHistory(
            new FileHistory('a', 'b',
                (new DateTimeImmutable())->setTimestamp(1094123420)) // 02.09.2004 - 13:10:20
        );
        $fileTwo->addHistory(
            new FileHistory('a', 'b',
                (new DateTimeImmutable())->setTimestamp(1494123420)) // 07.05.2017 - 04:17:00
        );
        $files = [
            $fileOne,
            $fileTwo,
        ];

        $report = $this->subject->generate(...$files);

        $expected = [
            [
                'filename' => 'file.txt',
                'total' => 3,
                'lastModifiedDate' => 'Sun, 07 May 17 02:17:00 +0000',
            ],
            [
                'filename' => 'sub_directory/something.txt',
                'total' => 2,
                'lastModifiedDate' => 'Thu, 12 Feb 09 07:24:00 +0000',
            ],
        ];

        $this->assertSame(json_encode($expected), $report);
    }
}
