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
use Freque\FileChangesHistoryLoader;
use Freque\FileHistory;
use Freque\JsonFormatter;
use Freque\Processor;
use Freque\Reporter;
use Freque\Runner;
use Freque\Utils\DirectoryPathNotFound;

class RunnerTest extends TestCase
{
    /**
     * @var Runner
     */
    private $subject;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var FileChangesHistoryLoader
     */
    private $loader;

    /**
     * @var Reporter
     */
    private $reporter;

    protected function setUp()
    {
        $this->processor = $this->prophesize(Processor::class);
        $this->loader    = $this->prophesize(FileChangesHistoryLoader::class);
        $this->reporter  = new Reporter(new JsonFormatter());

        $this->subject = new Runner(
            $this->processor->reveal(),
            $this->loader->reveal(),
            $this->reporter
        );
    }

    /**
     * @test
     */
    public function itLoadsFilesAndBuildsReport()
    {
        $repositoryPath = sprintf(
            '%s/testrepo/',
            __DIR__
        );

        $fileOne = new File('test.txt');
        $fileOne->addHistory(new FileHistory(
                'Stas',
                'stas@test.com',
                (new DateTimeImmutable())->setTimestamp(1340651949)
            )
        );
        $fileTwo = new File('test2.txt');
        $fileTwo->addHistory(new FileHistory(
                'Stas',
                'stas@test.com',
                (new DateTimeImmutable())->setTimestamp(1040651949)
            )
        );
        $files = [
            $fileOne,
            $fileTwo,
        ];
        $this->processor->loadByPath($repositoryPath)->willReturn(
            $files
        );

        $result = $this->subject->run(
            $repositoryPath
        );

        $expected =
            [
                [
                    'filename' => 'test.txt',
                    'total' => 1,
                    'lastModifiedDate' => 'Mon, 25 Jun 12 19:19:09 +0000',
                ],
                [
                    'filename' => 'test2.txt',
                    'total' => 1,
                    'lastModifiedDate' => 'Mon, 23 Dec 02 13:59:09 +0000',
                ],
            ];

        $this->assertSame($expected, \json_decode($result, true));
    }

    /**
     * @test
     */
    public function itOutputsDirectoryPathNotFoundExceptionOnLoadError()
    {
        $this->processor->loadByPath('testingPath')->willThrow(
            new DirectoryPathNotFound('Cannot find dir: testingPath'));

        $output = $this->subject->run('testingPath');

        $this->assertSame('Cannot find dir: testingPath', $output);
    }
}
