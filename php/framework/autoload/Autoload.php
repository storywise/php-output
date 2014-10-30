<?php

class AutoloadFolderFilter extends RecursiveFilterIterator {

        public function accept() {
                
                $file = $this->current();
                $isDir = $file->isDir();
                $fileName = $file->getFilename();
                
                // Two leading underscores means we skip the folder and its contents
                $isDisabledFolder = strpos($fileName, '__') !== false;

                if ($isDir && !$isDisabledFolder) {
                        return true;
                }
                return false;
        }

}

/**
 * @TODO Investigate implementation of 
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md 
 * standards
 */
class Autoload {

        public static function register() {
                if (function_exists('__autoload')) {
                        spl_autoload_register('__autoload');
                }
                return spl_autoload_register(array('Autoload', 'load'));
        }

        private static $frameworks;

        /**
         * Prior to registering the Autoloader, it's possible to define
         * framework folders in various locations.
         */
        public static function setFrameworkFolders() {
                self::$frameworks = array();
                self::setFrameworkFoldersIn('php');
                self::setFrameworkFoldersIn('serve/template');
        }

        /**
         * Allocate framework folders and configure in static array.
         * 
         * @param type $folder
         * @param type $alt
         */
        public static function setFrameworkFoldersIn($folder, $alt = true) {

                $dir = new RecursiveDirectoryIterator(ROOT . $folder, RecursiveDirectoryIterator::SKIP_DOTS);

                $address = new RecursiveIteratorIterator(
                                new AutoloadFolderFilter($dir),
                                // First self folder prior to children
                                RecursiveIteratorIterator::SELF_FIRST,
                                // Ignores exceptions thrown in getChildren
                                RecursiveIteratorIterator::CATCH_GET_CHILD);

                foreach ($address as $dir) {
                        $re = '/framework$/';
                        preg_match($re, $dir, $matches);
                        if (count($matches) > 0) {
                                $dir = str_replace('\\', '/', $dir);
                                array_push(self::$frameworks, $dir . '/');
                        }
                }
        }

        /**
         * Automatically called when a class is referred to in php code.
         * Doesn't invoke when the class has already been loaded before and so is made available.
         * 
         * @param type $class
         * @return boolean
         */
        public static function load($class) {

                if (!isset(self::$frameworks))
                        self::setFrameworkFolders();

                // Make first camel case class the folder name. Cant use ToolsString here because it needs the autoload to spawn
                preg_match_all('/((?:^|[A-Z])[a-z_]+)/', $class, $matches);
                $num = count($matches[0]);

                if (is_array($matches) && $num > 1) {
                        
                        // Create path parts
                        $folder = strtolower(implode('/', $matches[0]));
                        $classFolder = $folder . '/' . $class;
                        
                } else {
                        
                        $folder = strtolower($matches[0][0]);
                        $classFolder = $folder . '/' . $class;
                        
                }

                for ($i = 0; $i < count(self::$frameworks); $i++) {

                        $framework = self::$frameworks[$i];

                        // In primary framework folder
                        $path = $framework . $classFolder . '.php';

                        if (file_exists($path)) {
                                self::getClass($path);
                                return true;
                        }
                }
        }

        private static function getClass($path) {
                require_once $path;
        }

}

?>