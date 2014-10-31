<?php

/**
 * Media is the main wrapper for all media entries.
 * 
 * @author merten
 */
class Media extends Instance {

        /**
         * Get an instance of a media object, pass in first part of the mimetype
         * value before the /. For example image/jpg, gives the argument image,
         * to spawn its related media object.
         * @return Media
         */
        public static function get($path, $modifier = false) {

                // Get extension of file
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $folder = pathinfo($path, PATHINFO_DIRNAME);
                $filename = pathinfo($path, PATHINFO_BASENAME);

                // Until i get the filinfo extension to work, im hacking the mimetype as such
                // This break support for audio etc
                $mime = "image/{$extension}";

                if ($mime !== false) {
                        $majorType = strstr($mime, "/", true);
                        $types = array($majorType, $extension);
                        $inst = Instance::create('Media', $types, Instance::$NOVIEW);
                        $inst->setData(array(
                            'extension' => $extension,
                            'mediatype' => $mime,
                            'folder' => $folder . '/',
                            'file' => $filename,
                            'id' => filemtime($path),
                            'credit' => false,
                            'modifier' => $modifier
                                )
                        );
                        return $inst;
                } else
                        return false;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
                Bench::mark("Media");
        }

        /**
         * If alternative text overlay, then this is not necessary
         * @param type $credit
         */
        public function setCredit($credit) {
                $this->data['credit'] = $credit;
        }

        public function exists() {
                return file_exists($this->getPathRelative()) && is_readable($this->getPathRelative());
        }

        public function getMimeBasedClassname() {
                // Create class variable of mimetype
                $mime = $this->getData('mediatype');
                return strstr($mime, '/', true);
        }

        public function getExtension() {
                $extension = $this->getData('extension');
                return $extension;
        }

        /**
         * Get filename of the media, as it exists in the local filesystem.
         * Without folders just the filename.
         * @return string
         */
        public function getFilename() {
                return $this->getData('file');
        }

        /**
         * Get media specific folder based on id and hash 
         * existing inside the media archive. Without filename.
         * @return type
         */
        public function getFolder($relative = true) {
                return $this->getData('folder');
        }

        /**
         * Get absolute path as it exists in the local filesystem 
         * to the media content, including folder and filename
         * @param boolean $modifierConfig
         * @return string
         */
        public function getPathRelative($modifierId = false) {
                return $this->getFolder() . $this->getFilename();
        }

        /**
         * Get path to the media content, running throuhg the media controller
         * to use verification to display a thumbnail or raw file.
         * Be aware that you are looking at the abstract implemenation of Media.
         * There is also MediaImageJpg etc.
         * 
         * @param boolean $modifierConfig
         * @return string
         */
        public function getPath($modifierId = false) {
                return URL . $this->getFolder() . $this->getFilename();
        }

        public function getPathModified($modifier) {
                return $this->getPath($modifier);
        }

        public function has($key) {
                return isset($this->data[$key]);
        }

        public function getData($key = false) {
                if ($this->has($key) && isset($this->data[$key]))
                        return $this->data[$key];
                else if (!$this->has($key) && $this->has('__' . $key))
                        return $this->data['__' . $key];
                else if ($key === false)
                        return $this->data;
                return false;
        }

        public function setData($data) {
                $this->data = $data;
        }

        public function isDisplayable() {
                return false;
        }

}

?>