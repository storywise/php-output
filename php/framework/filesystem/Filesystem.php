<?php

class Filesystem {

        function __construct() {
                
        }

        /**
         * Should also support PNG/GIF/JPG
         * @param type $entry
         * @return type

          public function is_image($entry) {
          return $this->getExtension(strtolower($entry)) == 'jpg';
          } */
        public static function getExtension($filename) {
                return ToolsFile::getExtensionByPath($filename);
        }

        public function getDir($dir) {
                return $this->getTree($dir, false);
        }

        /**
         * This is still a rather oldschool approach with some functionality
         * borrowed from a php.com contributor. Soon i will update this
         * with a better Instance::get approach for different types of filesystem requests.
         * 
         * @param type $dir
         * @param type $deep
         * @param type $includeDirectory
         * @return type
         * @throws Exception
         */
        public function getTree($dir, $deep = true, $includeDirectory = true) {
                if (!is_dir($dir))
                        throw new Exception('Request getTree dir does not exist.');
                $path = '';
                $stack[] = $dir;
                while ($stack) {
                        $thisdir = array_pop($stack);
                        if ($dircont = scandir($thisdir)) {
                                $i = 0;
                                while (isset($dircont[$i])) {
                                        if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
                                                $current_file = "{$thisdir}/{$dircont[$i]}";
                                                if (is_file($current_file)) {
                                                        $path[] = "{$thisdir}/{$dircont[$i]}";
                                                } elseif (is_dir($current_file)) {
                                                        if ($deep) {
                                                                if ($includeDirectory)
                                                                        $path[] = "{$thisdir}/{$dircont[$i]}";
                                                                $stack[] = $current_file;
                                                        }
                                                }
                                        }
                                        $i++;
                                }
                        }
                }
                return $path;
        }

}

?>