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

use Freque\Utils\FileScanner;

class Processor
{
    /**
     * @var FileScanner
     */
    private $fileScanner;

    public function __construct(FileScanner $fileScanner)
    {
        $this->fileScanner = $fileScanner;
    }

    /**
     * @param string $path
     *
     * @return File[]
     */
    public function loadByPath(string $path): array
    {
        $rawFiles = $this->fileScanner->loadByPath($path);

        $filesForProcessing = [];
        foreach ($rawFiles as $file) {
            $filesForProcessing[] = new File($file);
        }

        return $filesForProcessing;
    }
}
