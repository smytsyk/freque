<?php
/*
 * This file is part of smytsyk-dev/freque.
 *
 * (c) Stas Mytsyk <denielm10@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Freque\Utils;


class GitFileHistoryLoader implements FileHistoryLoader
{
    /**
     * @var ShellExec
     */
    private $shellExec;

    public function __construct(ShellExec $shellExec)
    {
        $this->shellExec = $shellExec;
    }

    /**
     * @param string $repositoryPath
     * @param string $filename
     *
     * @return array
     * @throws RepositoryPathNotFound
     */
    public function getFileHistory(string $repositoryPath, string $filename): array
    {
        if (!is_dir($repositoryPath)) {
            throw new RepositoryPathNotFound(sprintf('Directory does not exist: %s', $repositoryPath));
        }

        $command = sprintf(
            'cd %s && git log --grep=Revert --invert-grep --pretty="format:%%an <%%ae> %%at" -- %s 2>/dev/null',
            $repositoryPath,
            $filename
        );

        $gitLogOutput = $this->shellExec->exec(
            $command
        );

        $logs = explode(PHP_EOL, $gitLogOutput);

        $historyData = [];
        foreach ($logs as $log) {
            preg_match(
                '/(?<author>.*) \<(?<email>.*)\> (?<unixtime>[0-9]+)/i',
                $log,
                $matches
            );
            $historyData[] = [
                'author' => $matches['author'] ?? '',
                'email' => $matches['email'] ?? '',
                'unixtime' => $matches['unixtime'] ?? 0,
            ];
        }

        return $historyData;
    }
}
