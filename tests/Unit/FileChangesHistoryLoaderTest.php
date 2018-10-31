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
use Freque\Utils\FileHistoryLoader;

class FileChangesHistoryLoaderTest extends TestCase
{
    /**
     * @var FileChangesHistoryLoader
     */
    private $subject;

    /**
     * @var FileHistoryLoader
     */
    private $historyLoader;

    protected function setUp()
    {
        $this->historyLoader = $this->prophesize(FileHistoryLoader::class);

        $this->subject = new FileChangesHistoryLoader($this->historyLoader->reveal());
    }

    /**
     * @test
     */
    public function itLoadsHistoryOfFileChanges()
    {
        $expectedFile = new File('test.txt');
        $expectedFile->addHistory(new FileHistory(
                'Stas',
                'stas@test.com',
                (new DateTimeImmutable())->setTimestamp(1540651949)
            )
        );
        $expectedFile->addHistory(new FileHistory(
            'Stas',
            'stas@test.com',
            (new DateTimeImmutable())->setTimestamp(1540651931)
        ));

        $file = new File('test.txt');

        $this->historyLoader->getFileHistory('repoPath', $file->filename())->willReturn(
            [
                [
                    'author' => 'Stas',
                    'email' => 'stas@test.com',
                    'unixtime' => '1540651949'
                ],
                [
                    'author' => 'Stas',
                    'email' => 'stas@test.com',
                    'unixtime' => '1540651931'
                ],
            ]
        );

        $this->subject->loadFileHistory('repoPath', $file);

        $this->assertEquals(
            $expectedFile,
            $file
        );
    }
}
