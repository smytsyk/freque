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

class ScandirFileScanner implements FileScanner
{
    const IGNORE = [
        '.',
        '..',
        '.git',
        '.idea',
        '.DS_Store',
    ];

    /**
     * @param string $path
     * @param string $localDir
     *
     * @return array
     * @throws DirectoryPathNotFound
     */
    public function loadByPath(string $path, string $localDir = ''): array
    {
        $path = str_replace('//', '/', $path);
        if (!is_dir($path)) {
            throw new DirectoryPathNotFound(
                sprintf('Cannot find dir: %s', $path)
            );
        }

        $scanResult = scandir($path);

        $files = [];

        foreach ($scanResult as $item) {
            if (\in_array($item, self::IGNORE)) {
                continue;
            }

            $fullpath = $path . '/' . $item;

            if (is_file($fullpath)) {
                $files[] = $path . $localDir . $item;

              continue;
            }

            if (is_dir($fullpath)) {
                $files = array_merge($files, $this->loadByPath($fullpath, '/'));
            }
        }

        return $files;
    }
}
