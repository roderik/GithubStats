GithubStats
===========

A Cilex command that used the Oregon project to compile statistics for a Github/Packagist organisation

## Usage

```
GithubStats version 1

Usage:
  [options] command [arguments]

Options:
  --help           -h Display this help message.
  --quiet          -q Do not output any message.
  --verbose        -v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
  --version        -V Display this application version.
  --ansi              Force ANSI output.
  --no-ansi           Disable ANSI output.
  --no-interaction -n Do not ask any interactive question.

Available commands:
  compile   Compiles the statistics for an organisation
  help      Displays help for a command
  list      Lists commands
```

or an example:

```
$ ./ghs compile Kunstmaan

Contributions:     5075
Contributors:      23
Forks:             68
Watchers:          451
Downloads total:   73523
Downloads monthly: 8689

```



## Common errors

API limit exeeded, just wait...
```
  [Github\Exception\ApiLimitExceedException]
  You have reached GitHub hour limit! Actual limit is: 5000
```
