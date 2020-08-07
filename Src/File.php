<?php
    namespace ACFileManager\Src;

    if(!defined('FULL_DEPTH')) {
        define('FULL_DEPTH' , 1);
    }
    if(!defined('CURRENT_DEPTH')) {
        define('CURRENT_DEPTH' , 0);
    }

    class File {
        private static $directories = [];
        private static $tree = [];

        private static function replaceLast($search, $replace, $subject) {
            $pos = strrpos($subject, $search);

            if($pos !== false) {
                $subject = substr_replace($subject, $replace, $pos, strlen($search));
            }

            return $subject;
        }

        public static function scanPath($path , $order = SCANDIR_SORT_ASCENDING) {
            return scandir($path , $order);
        }

        public static function filterScan($path , $filters , $order = SCANDIR_SORT_ASCENDING) {
            return array_diff(self::scanPath($path , $order), $filters);
        }

        public static function getFiles($path , $regex = null , $depth = CURRENT_DEPTH , $order = SCANDIR_SORT_ASCENDING) {
            $directories_list = [$path => false];
            $result = [];

            $listDirectories = function() use (&$directories_list , &$listDirectories) {
                foreach($directories_list as $directory => $status) {
                    if(!$status) {
                        foreach(self::getDirectories($directory) as $dir) {
                            $directories_list[$dir] = false;
                        }
                        $directories_list[$directory] = true;
                        $listDirectories();
                    }
                }
            };

            if($depth === FULL_DEPTH) {
                $listDirectories();
            }

            foreach($directories_list as $p => $status) {
                $scan = self::filterScan($p , ['..' , '.'] , $order);
                foreach ($scan as $key => $value) {
                    if (!is_dir($p . DIRECTORY_SEPARATOR . $value))
                    {
                        if(isset($regex) && !empty($regex)) {
                            if(preg_match($regex , $value)) {
                                array_push($result , $p . DIRECTORY_SEPARATOR . $value);
                            }
                        } else {
                            array_push($result , $p . DIRECTORY_SEPARATOR . $value);
                        }
                    }
                }
            }

            return $result;
        }

        public static function getDirectories($path , $regex = null , $depth = CURRENT_DEPTH , $order = SCANDIR_SORT_ASCENDING) {
            $directories_list = [$path => false];
            $result = [];

            $listDirectories = function() use (&$directories_list , &$listDirectories , $order , $path , $depth) {
                foreach($directories_list as $directory => $status) {
                    if(!$status) {
                        $scan = self::filterScan($directory , ['..' , '.'] , $order);
                        foreach ($scan as $key => $value) {
                            if (is_dir($directory . DIRECTORY_SEPARATOR . $value))  {
                                $directories_list[$directory . DIRECTORY_SEPARATOR . $value] = false;
                            }
                        }
                        if($directory === $path) {
                            unset($directories_list[$directory]);
                        } else {
                            $directories_list[$directory] = true;
                        }
                        if($depth === FULL_DEPTH) {
                            $listDirectories();
                        }
                    }
                }
            };
            $listDirectories();

            foreach($directories_list as $d => $status) {
                if(isset($regex) && !empty($regex)) {
                    if(preg_match($regex , basename($d))) {
                        array_push($result , $d);
                    }
                } else {
                    array_push($result , $d);
                }
            }

            return $result;
        }

        public static function exists($path) {
            return file_exists($path);
        }

        public static function deleteFile($path) {
            if(self::exists($path)) {
                return unlink($path);
            }

            return false;
        }

        public static function deleteDirectory($path){
            if(self::exists($path)) {
                self::emptyDirectory($path);
                return rmdir($path);
            }

            return false;
        }

        public static function emptyDirectory($path , $except = [] , $self_delete = false) {
            $dir_handle = null;

            if (is_dir($path))
                $dir_handle = opendir($path);

            if (!$path)
                return false;

            while($file = readdir($dir_handle)) {
                if ($file != "." && $file != ".." && !in_array($path."/".$file , $except)) {
                    if (!is_dir($path."/".$file))
                        @unlink($path."/".$file);
                    else
                        self::emptyDirectory($path.'/'.$file, $except , true);
                }
            }
            closedir($dir_handle);
            if ($self_delete){
                @rmdir($path);
            }
            return true;
        }

        public static function makeDirectory($path , $mode = 0777) {
            if(!self::exists($path)) {
                return mkdir($path , $mode);
            }

            return false;
        }

        public static function makeFile($path , $mode = "w") {
            return fopen($path, $mode);
        }

        public static function addFileContent($path , $content , $mode = "w") {
            $file = self::makeFile($path , $mode);
            fwrite($file, $content);
            fclose($file);
            return true;
        }

        public static function copyDirectoryRecursively($from , $to , $create_base = true) {
            if($create_base) {
                $to = $to . DIRECTORY_SEPARATOR . self::getBaseName($from);
                self::makeDirectory($to);
            }
            $dir = opendir($from);
            @mkdir($to);
            while(false !== ($file = readdir($dir)) ) {
                if (( $file != '.' ) && ($file != '..' )) {
                    if (is_dir($from . '/' . $file)) {
                        self::copyDirectoryRecursively($from . '/' . $file,$to . '/' . $file , false);
                    }
                    else {
                        copy($from . '/' . $file,$to . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }

        public static function copyFile($from , $to) {
            return copy($from, $to);
        }

        public static function rename($path , $new_name) {
            $path = self::cleanPath($path);

            return rename(
                $path ,
                self::replaceLast(basename($path) , '' , $path) . $new_name
            );
        }

        public static function moveFile($from_dir , $to_dir , $name) {
            $from_dir = self::cleanPath($from_dir);
            if(!self::exists("$from_dir/$name")) {
                return false;
            }

            return rename("$from_dir/$name" , "$to_dir/$name");
        }

        public static function moveDirectory($from_dir , $to_dir , $dir) {
            $from_dir = self::cleanPath($from_dir);
            if(!self::exists("$from_dir/$dir")) {
                return false;
            }

            return rename("$from_dir/$dir" , "$to_dir/$dir");
        }

        public static function isFileEmpty($path) {
            clearstatcache();
            if(filesize($path)) {
                return false;
            }

            return true;
        }

        public static function isDirectoryEmpty($path) {
            $path = self::cleanPath($path);
            return count(glob("$path/*")) === 0;
        }

        public static function cleanPath($path) {
            $path = rtrim($path , '/');
            return rtrim($path , '\\');
        }

        public static function fixPath($path) {
            return self::cleanPath(str_replace(
                DIRECTORY_SEPARATOR === '/' ? '\\' : '/' ,
                DIRECTORY_SEPARATOR ,
                $path));
        }

        public static function getDirectoryTree($path) {
            $result = [];
            self::$directories[$path] = 'true';
            $files = self::getFiles($path);
            $dirs = self::getDirectories($path);
            if(!isset(self::$tree[$path]) && !empty($dirs) && !empty($files)) {
                self::$tree[$path] = [];
            }
            foreach ($files as $file) {
                self::$tree[$path][$file] = 'file';
            }
            foreach ($dirs as $dir) {
                self::$tree[$path][$dir] = 'directory';
                self::$directories[$dir] = 'false';
            }
            foreach (self::$directories as $directory => $status) {
                if($status === 'false') {
                    self::getDirectoryTree($directory);
                }
            }
            foreach (self::$tree as $t => $array) {
                $result[$t] = [];
                foreach ($array as $key => $value) {
                    $result[$t][self::getBaseName($key)] = $value;
                }
            }
            return $result;
        }

        public static function getBaseName($path) {
            return basename($path);
        }

        public static function getFileContent($path) {
            return file_get_contents($path);
        }
    }
?>
