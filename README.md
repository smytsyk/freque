# Freque

The library to get stats how frequenlty files are changed based on GIT commits information.

## Requirements

- PHP 7 or above.
- GIT

## Installation

Install the latest version with

```bash
$ composer require smytsyk-dev/freque
```

## Basic Usage

```bash
./vendor/bin/freque path_to_GIT_directory
```

### Example of the output

```
| #    | Total | Last Modified Date             | File
--------------------------------------------------------------------------------
| 1    |     5 | Sun, 28 Oct 18 18:45:00 +0000  | composer.json
| 2    |     3 | Sun, 28 Oct 18 20:36:05 +0000  | freque
| 3    |     3 | Sun, 28 Oct 18 19:52:56 +0000  | src/Runner.php
| 4    |     2 | Sun, 28 Oct 18 18:45:00 +0000  | src/JsonFormatter.php
| 5    |     2 | Sun, 28 Oct 18 18:45:00 +0000  | src/Reporter.php
| 6    |     2 | Sun, 28 Oct 18 13:48:34 +0000  | src/File.php
| 7    |     2 | Sun, 28 Oct 18 18:45:00 +0000  | src/Formatter.php
| 8    |     2 | Sat, 27 Oct 18 21:03:15 +0000  | src/Processor.php
```

## Formatter

The default formatter is "Console"

There is a way to get the output in JSON

```bash
./vendor/bin/freque --formatter=json /Library/WebServer/Documents/file_stats/freque
```

## Advanced Usage

You can introduce your own formatter, file scanner or any other component.
The design allows adding new requirements and modifying the behavior.

```php
use Freque\FileChangesHistoryLoader;
use Freque\JsonFormatter;
use Freque\Processor;
use Freque\Reporter;
use Freque\Runner;
use Freque\Utils\GitFileHistoryLoader;
use Freque\Utils\ScandirFileScanner;
use Freque\Utils\ShellExec;

$runner = new Runner(new Processor(new ScandirFileScanner()),
    new FileChangesHistoryLoader(new GitFileHistoryLoader(new ShellExec())),
    new Reporter(new JsonFormatter())
);

$targetGitRepositoryDir = ''

echo $runner->run($targetGitRepositoryDir) . PHP_EOL;
```

## License

This library is released under the [MIT license](LICENSE).