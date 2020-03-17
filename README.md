<h1 align="center">
  ACFile
</h1>

PHP library for file and directory management

## Requirements

This library is supported by PHP versions 5.6 or higher

## Installation

The preferred way to install this library is through [Composer](https://getcomposer.org/) :
```ssh
composer require acide/acfile
```

## Available Methods

```php
File::exists($path)
```

Method | Description | Return
------- ------------- -------
exists($path) | Check if a file or directory exists in a path | Boolean

Attribute | Description | Type | Required | Default
---------- ------------- ------ ---------- ---------
$path |File or directory path | String | No |

```php
File::scanPath($path , $order);
```