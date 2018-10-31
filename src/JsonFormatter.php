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

class JsonFormatter implements Formatter
{
    /**
     * JsonFormater constructor.
     * @param Report[] $reports
     * @return string
     */
    public function format(Report ...$reports): string
    {
        return json_encode($reports);
    }
}
