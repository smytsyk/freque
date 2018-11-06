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

use Freque\Utils\DirectoryPathNotFound;

class Runner
{
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

    public function __construct(
        Processor $processor,
        FileChangesHistoryLoader $loader,
        Reporter $reporter
    )
    {
        $this->processor = $processor;
        $this->loader    = $loader;
        $this->reporter  = $reporter;
    }

    public function run(string $repositoryPath): string
    {
        try {
            $files = $this->processor->loadByPath($repositoryPath);
        } catch (DirectoryPathNotFound $e) {
            return $e->getMessage();
        }
        foreach ($files as $file) {
            $this->loader->loadFileHistory($repositoryPath, $file);
        }

        return $this->reporter->generate($repositoryPath, ...$files);
    }
}