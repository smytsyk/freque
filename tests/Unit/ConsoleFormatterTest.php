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
use Freque\ConsoleFormatter;
use Freque\Report;

class ConsoleFormatterTest extends TestCase
{
    /**
     * @var ConsoleFormatter
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new ConsoleFormatter();
    }

    /**
     * @test
     */
    public function itGeneratesConsoleReadableOutputFromReports()
    {
        $reports[] = new Report(
            'test.xml',
            23,
            'Mon, 25 Jun 12 19:19:09 +0000'
        );

        $reports[] = new Report(
            'nestedLevel/files.xml',
            15,
            'Mon, 17 Jun 12 19:19:09 +0000'
        );

        $output = $this->subject->format(...$reports);

        $expected = <<<EOD

| #    | Total | Last Modified Date             | File 
--------------------------------------------------------------------------------
| 1    |    23 | Mon, 25 Jun 12 19:19:09 +0000  | test.xml 
| 2    |    15 | Mon, 17 Jun 12 19:19:09 +0000  | nestedLevel/files.xml 

EOD;

        $this->assertSame($expected, $output);
    }
}
