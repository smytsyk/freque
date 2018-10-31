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
use Freque\Utils\GitFileHistoryLoader;

class GitHistoryLoaderTest extends TestCase
{
    /**
     * @var ShellExec
     */
    private $shellExec;

    /**
     * @var GitFileHistoryLoader
     */
    private $subject;

    protected function setUp()
    {
        $this->shellExec = $this->prophesize(ShellExec::class);

        $this->subject = new GitFileHistoryLoader($this->shellExec->reveal());
    }

    /**
     * @test
     */
    public function itReturnsAuthorAndDateFromGitLogForFile()
    {
        $repositoryPath = sprintf(
            '%s/testrepo/',
            __DIR__
        );

        $filename = 'nested_level/new_file.txt';

        $execOutput = <<<EOD
Stas <stas@test.com> 1540847386
Stas <stas@test.com> 1240847386
Stas <stas@test.com> 1140847386
EOD;

        $command = sprintf(
            'cd %s && git log --pretty="format:%%an <%%ae> %%at" -- %s 2>/dev/null',
            $repositoryPath,
            $filename
        );

        $this->shellExec->exec($command)->willReturn($execOutput);

        $result = $this->subject->getFileHistory(
            $repositoryPath,
            $filename
        );

        $this->assertSame(
            [
                [
                    'author' => 'Stas',
                    'email' => 'stas@test.com',
                    'unixtime' => '1540847386',
                ],
                [
                    'author' => 'Stas',
                    'email' => 'stas@test.com',
                    'unixtime' => '1240847386',
                ],
                [
                    'author' => 'Stas',
                    'email' => 'stas@test.com',
                    'unixtime' => '1140847386',
                ],
            ],
            $result
        );
    }

    /**
     * @test
     * @expectedException \Freque\Utils\RepositoryPathNotFound
     */
    public function itThrowsExceptionWhenRepositoryPathDoesNotExist()
    {
        $this->subject->getFileHistory(
            sprintf(
                '%s/NonExistingRepositoryPath/',
                __DIR__
            ),
            'nested_level/new_file.txt'
        );
    }
}
