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

class File
{
    /**
     * @var string string
     */
    private $filename;

    /**
     * @var FileHistory[]
     */
    private $history = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function addHistory(FileHistory $history)
    {
        $this->history[] = $history;
    }

    public function numOfChanges(): int
    {
        return count($this->history);
    }

    public function lastModifiedDate(): \DateTimeImmutable
    {
        $lastDate = null;
        foreach ($this->history as $history) {
            if ($history->getDate() > $lastDate) {
                $lastDate = $history->getDate();
            }
        }

        if (!$lastDate) {
            return new \DateTimeImmutable();
        }

        return $lastDate;
    }

}
