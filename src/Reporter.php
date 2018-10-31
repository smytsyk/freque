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

use DateTime;

class Reporter
{
    /**
     * @var Formatter
     */
    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function generate(File ...$files): string
    {
        $report = $this->buildReport(...$files);

        return $this->formatter->format(...$report);
    }

    /**
     * @param File[] ...$files
     *
     * @return Report[]
     */
    private function buildReport(File  ...$files): array
    {
        $report = [];
        foreach ($files as $file) {
            $report[] = new Report(
                $file->filename(),
                $file->numOfChanges(),
                $file->lastModifiedDate()->format(DateTime::RFC1036)
            );
        }

        usort($report, function (Report $a, Report $b) {
            return $a->total() < $b->total();
        });

        return $report;
    }
}