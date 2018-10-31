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


class ConsoleFormatter implements Formatter
{

    const HEADER_SEPARATOR_LENGTH = 80;

    public function format(Report ...$reports): string
    {
        $mask = "| %-4.5s | %5.5s | %-30.30s | %s \n";

        $output = PHP_EOL;
        $output .= sprintf($mask, '#', 'Total', 'Last Modified Date', 'File');
        $output .= str_repeat('-', self::HEADER_SEPARATOR_LENGTH) . PHP_EOL;
        $number = 0;

        foreach ($reports as $report) {
            $number++;

            $output .= sprintf($mask, $number, $report->total(), $report->lastModifiedDate(), $report->filename());
        }

        return $output;
    }
}
