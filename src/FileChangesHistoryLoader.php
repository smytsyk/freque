<?php
/*
 * This file is part of smytsyk-dev/freque.
 *
 * (c) Stas Mytsyk <denielm10@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Freque;

use DateTimeImmutable;
use Freque\Utils\FileHistoryLoader;

class FileChangesHistoryLoader
{
    /**
     * @var FileHistoryLoader
     */
    private $loader;

    public function __construct(FileHistoryLoader $loader)
    {
        $this->loader = $loader;
    }

    public function loadFileHistory(string $repoPath, File $file)
    {
        $historyRaw = $this->loader->getFileHistory($repoPath, $file->filename());

        foreach ($historyRaw as $item) {
            $file->addHistory(new FileHistory(
                $item['author'],
                $item['email'],
                (new DateTimeImmutable())->setTimestamp($item['unixtime'])
            ));
        }
    }
}
