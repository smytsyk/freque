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

class Report implements \JsonSerializable
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $total;

    /**
     * @var string
     */
    private $lastModifiedDate;

    public function __construct(string $filename, int $total, string $lastModifiedDate)
    {
        $this->filename         = $filename;
        $this->total            = $total;
        $this->lastModifiedDate = $lastModifiedDate;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function lastModifiedDate(): string
    {
        return $this->lastModifiedDate;
    }

    public function jsonSerialize()
    {
        return [
            'filename' => $this->filename,
            'total' => $this->total,
            'lastModifiedDate' => $this->lastModifiedDate,
        ];

    }
}
