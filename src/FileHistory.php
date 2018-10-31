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

class FileHistory
{
    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $authorEmail;

    public function __construct(string $authorName, string $authorEmail, DateTimeImmutable $date)
    {
        $this->date        = $date;
        $this->authorName  = $authorName;
        $this->authorEmail = $authorEmail;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }
}
