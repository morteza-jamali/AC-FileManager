<?php
    namespace ACFile\Src;

    class File {
        public static function scanPath($path , $order = SCANDIR_SORT_ASCENDING) {
            return scandir($path , $order);
        }

        public static function filterScan($path , $filters , $order = SCANDIR_SORT_ASCENDING) {
            return array_diff(self::scanPath($path , $order), $filters);
        }

        public static function getFiles($path , $order = SCANDIR_SORT_ASCENDING) {
            $result = [];
            $scan = self::filterScan($path , ['..' , '.'] , $order);
            foreach ($scan as $key => $value) {
                if (!is_dir($path . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$key] = $path . DIRECTORY_SEPARATOR . $value;
                }
            }

            return $result;
        }

        public static function getDirectories($path , $order = SCANDIR_SORT_ASCENDING) {
            $result = [];
            $scan = self::filterScan($path , ['..' , '.'] , $order);
            foreach ($scan as $key => $value) {
                if (is_dir($path . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$key] = $path . DIRECTORY_SEPARATOR . $value;
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

        public static function emptyDirectory($path) {
            $path = self::cleanPath($path);
            $files = glob("$path/{,.}*", GLOB_BRACE);
            foreach($files as $file){
                if(is_file($file))
                    unlink($file);
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

        public static function copyDirectoryRecursively($from , $to) {
            $dir = opendir($from);
            @mkdir($to);
            while(false !== ($file = readdir($dir)) ) {
                if (( $file != '.' ) && ($file != '..' )) {
                    if (is_dir($from . '/' . $file)) {
                        self::copyDirectory($from . '/' . $file,$to . '/' . $file);
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

        public static function rename($parent_path , $old_name , $new_name) {
            $parent_path = self::cleanPath($parent_path);
            if(!self::exists("$parent_path/$old_name")) {
                return false;
            }

            return rename("$parent_path/$old_name" , "$parent_path/$new_name");
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
    }
?>