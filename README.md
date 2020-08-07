<h1 align="center">
  AC FileManager
</h1>

<p align="center">
<img src="https://img.shields.io/badge/version-2.1.1-orange" alt="version">
<img src="https://img.shields.io/badge/license-CC0--1.0-yellowgreen" alt="license">
<img src="https://img.shields.io/badge/size-120%20KB-lightgrey" alt="size">
</p>

PHP library for file and directory management

## Requirements

This library is supported by PHP versions 5.6 or higher

## Installation

The preferred way to install this library is through [Composer](https://getcomposer.org/) :
```ssh
composer require acide/acfilemanager
```

## Quick Start

To use this library with Composer :

```php
require __DIR__ . '/vendor/autoload.php';

use ACFileManager\Src\File;
```

## Available Methods

```php
File::exists($path)
```

Method | Description | Return
-------|-------------|-------
exists | Check if a file or directory exists in a path | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path |File or directory path | String | Yes |

```php
File::scanPath($path , $order);
```

Method | Description | Return
-------|-------------|-------
scanPath | List files and directories inside the specified path | Array

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$order | Sorting order | Int | No | SCANDIR_SORT_ASCENDING

If the optional __$order__ is set to __SCANDIR_SORT_DESCENDING__, then the sort order is alphabetical in descending order. If it is set to __SCANDIR_SORT_NONE__ then the result is unsorted.

```php
File::filterScan($path , $filters , $order)
``` 

Method | Description | Return
-------|-------------|-------
filterScan | Filters and remove an array from scan | Array

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$filters | Array of filtered directories and files | Array | Yes |
$order | Sorting order | Int | No | SCANDIR_SORT_ASCENDING

__# A simple filterScan() example__
```php
$result = File::filterScan(
    '/path/tmp' ,
    array('readme.txt' , 'sources')
);

print_r($result);
```

Lists all files and directories in `/path/tmp` and removes `readme.txt` file and `sources` directory from result

```php
File::getFiles($path , $regex , $depth , $order)
```

Method | Description | Return
-------|-------------|-------
getFiles | Lists all files in a path | Array

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$regex | Regex for matching file name | String | No | null
$depth | Depth for searching files | Int | No | CURRENT_DEPTH
$order | Sorting order | Int | No | SCANDIR_SORT_ASCENDING

```php
File::getDirectories($path , $regex , $depth , $order)
```

Method | Description | Return
-------|-------------|-------
getDirectories | Lists all directories in a path | Array

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$regex | Regex for matching directory name | String | No | null
$depth | Depth for searching directories | Int | No | CURRENT_DEPTH
$order | Sorting order | Int | No | SCANDIR_SORT_ASCENDING

```php
File::deleteFile($path)
```

Method | Description | Return
-------|-------------|-------
deleteFile | Delete file if exists | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File path | String | Yes |

```php
File::deleteDirectory($path)
```

Method | Description | Return
-------|-------------|-------
deleteDirectory | Delete a folder with its content | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |

```php
File::emptyDirectory($path , $except , $self_delete)
```

Method | Description | Return
-------|-------------|-------
emptyDirectory | Delete all files and directories from directory | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$self_delete | Delete directory itself | Boolean | No | False
$except | Doesn't remove these files and directories | Array | No | []

__# A simple emptyDirectory() example__
```php
File::emptyDirectory('/to/path/sample' , [
    '/to/path/sample/Application' ,
    '/to/path/sample/webpack.config.js'
]);
```

```php
File::makeDirectory($path , $mode)
```

Method | Description | Return
-------|-------------|-------
makeDirectory | Creates a directory | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |
$mode | Mode number | Int | No | 0777

The mode is 0777 by default, which means the widest possible access. For more information on modes, read the details on the [chmod()](https://www.php.net/manual/en/function.chmod.php) page.

```php
File::addFileContent($path , $content , $mode)
```

Method | Description | Return
-------|-------------|-------
addFileContent | Add content to a file | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File path | String | Yes |
$content | Content for writing | String | Yes |
$mode | Writing Mode | String | No | w

If you use addFileContent() on a file that does not exist, it will create it, given that the file is opened for writing __(w)__ or appending __(a)__

__# A simple addFileContent() example__
```php
$result = File::addFileContent(
    '/path/tmp/doc.txt' ,
    'This is for test :)'
);

var_dump($result);
```

```php
File::copyDirectoryRecursively($from , $to)
```

Method | Description | Return
-------|-------------|-------
copyDirectoryRecursively | Copies directory recursively |

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$from | From directory path | String | Yes |
$to | Destination directory path | String | Yes |

```php
File::copyFile($from , $to)
```

Method | Description | Return
-------|-------------|-------
copyFile | Copies file content | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$from | From file path | String | Yes |
$to | Destination file path | String | Yes |

```php
File::rename($path , $new_name)
```

Method | Description | Return
-------|-------------|-------
rename | Renames a file or directory | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory or file path | String | Yes |
$new_name | New name | String | Yes |

__# A simple rename() example__

```php
$result = rename(
    '/path/tmp/File1.txt' ,
    'File2.txt'
);

var_dump($result);
```

```php
File::moveFile($from_dir , $to_dir , $name)
```

Method | Description | Return
-------|-------------|-------
moveFile | Moves file to another directory | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$from_dir | Parent directory path | String | Yes |
$to_dir | Destination directory path | String | Yes |
$name | File name | String | Yes |

__# A simple moveFile() example__

```php
$result = File::moveFile(
    '/path/tmp' ,
    '/path/another' ,
    'Files.txt'
);

var_dump($result);
```

Moves `Files.txt` in `/path/tmp` directory to `/path/another` directory

```php
File::moveDirectory($from_dir , $to_dir , $dir)
```

Method | Description | Return
-------|-------------|-------
moveDirectory | Moves directory | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$from_dir | Parent directory path | String | Yes |
$to_dir | Destination directory path | String | Yes |
$dir | Directory name | String | Yes |

```php
File::isFileEmpty($path)
```

Method | Description | Return
-------|-------------|-------
isFileEmpty | Check if file is empty | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File path | String | Yes |

```php
File::isDirectoryEmpty($path)
```

Method | Description | Return
-------|-------------|-------
isDirectoryEmpty | Check if directory is empty | Boolean

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |

```php
File::cleanPath($path)
```

Method | Description | Return
-------|-------------|-------
cleanPath | Removes last / and \ from path | String

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File or directory path | String | Yes |

__# A simple cleanPath() example__

```php
$result = File::cleanPath('path/tmp/');

echo $result;
```

Result is `path/tmp`

```php
File::fixPath($path)
```

Method | Description | Return
-------|-------------|-------
fixPath | Fixes path string problems | String

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File or directory path | String | Yes |

__# A simple fixPath() example in Linux OS__

```php
$result = File::fixPath('var/www/html/acide\images\test.png');

echo $result;
```

Result is `var/www/html/acide/images/test.png`

```php
File::getDirectoryTree($path)
```

Method | Description | Return
-------|-------------|-------
getDirectoryTree | Get all files and directories | Array

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |

```text
--- /root
    |
    |----- /src
    |      |----- main.js
    |      |----- home.js
    |
    |----- example.text
    |----- simple.php 
```

The file manager returns the above file structure as follows :

```php
array (size=2)
  '../root' => 
    array (size=3)
      'src' => string 'directory' (length=3)
      'example.text' => string 'file' (length=12)
      'simple.php' => string 'file' (length=10)
  '../root/src' => 
    array (size=2)
      'main.js' => string 'file' (length=7)
      'home.js' => string 'file' (length=7)
```

```php
File::getBaseName($path)
```

Method | Description | Return
-------|-------------|-------
getBaseName | Get path base name | String

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | Directory path | String | Yes |

```php
File::getFileContent($path)
```

Method | Description | Return
-------|-------------|-------
getFileContent | Returns file content | String

Attribute | Description | Type | Required | Default
----------|-------------|------|----------|---------
$path | File path | String | Yes |

## License

This project is licensed under Creative Commons Zero v1.0 Universal license. See the [LICENSE](https://github.com/morteza-jamali/AC-FileManager/blob/master/LICENSE) file for more info.