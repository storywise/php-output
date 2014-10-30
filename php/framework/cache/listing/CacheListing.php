<?php

require ROOT . "php/vendor/jsmin/jsmin.php";

class CacheListing extends Filesystem {

        public $target;
        public $cache;
        private $files;
        private $extension;

        /**
         * 
         * @param type $files
         * @param type $target
         * @param type $ext
         */
        function __construct($files, $ext) {

                $this->extension = $ext;
                $this->cache = FOLDER_CACHE;
                
                if (!is_dir($this->cache))
                        mkdir($this->cache);
                
                $this->files = $files;
                $this->contents = "/* (c) Author */\n\n";
                if (LOCAL)
                        $this->contents .= $this->getSignatureNewFile("Cache created on " . date("F jS Y H:i:s", time()));
        }

        private function getSignatureNewFile($signature) {
                return "\n\n/* " . $signature . "\n**************************************/\n";
        }

        public function getCacheStatus($flushCache = false) {

                $result = array(
                    'lastModified' => false,
                    'isCurrent' => false,
                    'numFiles' => 0);

                foreach ($this->files as $filePath) {

                        if ($this->getExtension($filePath) != $this->extension) {
                                continue;
                        }

                        if ($flushCache) {

                                if (LOCAL)
                                        $this->contents .= $this->getSignatureNewFile(rtrim(basename($filePath), '.' . $this->extension));

                                if ($this->extension == "js") {
                                        if (!LOCAL)
                                                $this->contents .= JSMin::minify(file_get_contents($filePath));
                                        else
                                                $this->contents .= file_get_contents($filePath);
                                } else {

                                        // Check if the file name marks that we need to replace some stuffs
                                        $isURLReplace = strpos($filePath, '_uri');

                                        // Get the contents for the css file
                                        $cssContent = file_get_contents($filePath);

                                        // Replace if necessary
                                        if ($isURLReplace) {
                                                $cssContent = str_replace('[ABS_ROOT]', ABS_ROOT, $cssContent);
                                        }

                                        // Append to css file
                                        $this->contents .= $cssContent;
                                }
                        }
                        
                        $fileModifiedAt = filemtime($filePath);
                        if ($fileModifiedAt > $result['lastModified'])
                                $result['lastModified'] = $fileModifiedAt;

                        $result['numFiles']++;
                }

                // If no files present, use the folder's modified date
                if ($result['numFiles'] == 0) {
                        return $result;
                }

                $cachePath = $this->getCacheFilePath($result['lastModified'], $this->files);
                $cacheExists = is_file($cachePath);

                if ($flushCache) {
                        file_put_contents($cachePath, $this->contents);
                        touch($cachePath, $result['lastModified']);
                }

                if ($cacheExists) {
                        $result['isCurrent'] = true;
                        return $result;
                } else {
                        return $result;
                }
        }

        private function getCacheFilePath($lastModified, $files) {
                $cacheKey = md5(implode('', $files) . $lastModified);
                return $this->cache . $cacheKey . "." . $this->extension;
        }

        public function getPath() {

                $cacheStatus = $this->getCacheStatus();

                if ($cacheStatus['numFiles'] == 0) {
                        return false;
                }

                if (!$cacheStatus['isCurrent']) {
                        $cacheStatus = $this->getCacheStatus(true);
                }

                return $this->getCacheFilePath($cacheStatus['lastModified'], $this->files);
        }

}

?>
