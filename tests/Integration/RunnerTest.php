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

use Freque\Utils\ShellExec;
use PHPUnit\Framework\TestCase;
use Freque\FileChangesHistoryLoader;
use Freque\JsonFormatter;
use Freque\Processor;
use Freque\Reporter;
use Freque\Runner;
use Freque\Utils\GitFileHistoryLoader;
use Freque\Utils\ScandirFileScanner;
use Prophecy\Argument;

class RunnerTest extends TestCase
{
    /**
     * @var ShellExec
     */
    private $shellExec;

    /**
     * @var Runner
     */
    private $subject;

    protected function setUp()
    {
        $this->shellExec = $this->prophesize(ShellExec::class);

        $this->subject = new Runner(new Processor(new ScandirFileScanner()),
            new FileChangesHistoryLoader(new GitFileHistoryLoader($this->shellExec->reveal())),
            new Reporter(new JsonFormatter())
        );
    }

    /**
     * @test
     */
    public function itLoadsFilesAndBuildsReport()
    {
        $execOutput = <<<EOD
Stas <stas@test.com> 1540847386
Stas <stas@test.com> 1240847386
Stas <stas@test.com> 1140847386
EOD;

        $this->shellExec->exec(Argument::any())->willReturn($execOutput);

        $result = $this->subject->run(
            sprintf(
                '%s/testrepo/',
                __DIR__
            ));

        $expected =
            [
                'filename' => 'fileOne.txt',
                'total' => 3,
                'lastModifiedDate' => 'Mon, 29 Oct 18 21:09:46 +0000',
            ];

        $this->assertSame($expected, \json_decode($result, true)[0]);
    }
}
