<?php

/**
 * Media is the main wrapper for all media entries.
 * 
 * @author merten
 */
class Media extends Instance {

        public static function isType(array $data, $type) {
                $index = isset($data['mediatype']) ? 'mediatype' : '__mediatype';
                return strpos($data[$index], $type . '/') !== false;
        }

        public static function canGet(array $data) {
                if (!isset($data['media_id']) && !isset($data['__media_id']))
                        return false;

                if (!isset($data['mediatype']) && !isset($data['__mediatype']))
                        return false;

                if (!isset($data['extension']) && !isset($data['__extension']))
                        return false;

                return true;
        }

        /**
         * Get an instance of a media object, pass in first part of the mimetype
         * value before the /. For example image/jpg, gives the argument image,
         * to spawn its related media object.
         * 
         * @return Media
         */
        public static function get(array $data) {
                if (!isset($data['media_id']) && !isset($data['__media_id']))
                        throw new Exception('media_id property is required in class Media');

                if (!isset($data['mediatype']) && !isset($data['__mediatype']))
                        throw new Exception('mediatype property is required in class Media');

                if (!isset($data['extension']) && !isset($data['__extension']))
                        throw new Exception('extension property is required in class Media');

                $mime = isset($data['mediatype']) ? $data['mediatype'] : ( isset($data['__mediatype']) ? $data['__mediatype'] : false );
                if ($mime !== false) {
                        $majorType = strstr($mime, '/', true);
                        $extension = isset($data['extension']) ? $data['extension'] : ( isset($data['__extension']) ? $data['__extension'] : false );
                        if ($extension !== false) {
                                $types = array($majorType, $extension);
                                $inst = Instance::create('Media', $types, Instance::$NOVIEW);
                                $inst->setData($data);
                                return $inst;
                        }
                } else
                        return false;
        }

        /**
         * Just to make sure the media/hash access token is not the same
         * as the encrypted token used to access the file directly in the url,
         * we pump another layer of encryption over the md5 hash and
         * store the raw image with that filename.
         * 
         * @param type $hash
         * @return type
         */
        public static function getEncryptedFilename($hash) {
                return Hash::create(USE_ENCRYPT, $hash, HASH_PW_KEY);
        }

        public static function getDatabaseFolder() {
                return DB_NAME_DEFAULT . '/';
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
                Bench::mark("Media");
        }

        public function exists() {
                return file_exists($this->getPathRelative()) && is_readable($this->getPathRelative());
        }

        public function validate() {
                return isset($this->data) && $this->has('hash') && $this->has('extension');
        }

        public function getMimeBasedClassname() {
                // Create class variable of mimetype
                $mime = $this->getData('mediatype');
                return strstr($mime, '/', true);
        }

        public function getIcon() {
                return $this->getData('icon');
        }

        /* public function hasAlbum() {
          return !empty($this->data['__album']) || !empty($this->data['album']);
          } */

        public function getWatermark() {
                return $this->getPath(MediaProcessor::MMID_WATERMARK);
        }

        public function getMedium() {
                return $this->getPath(MediaProcessor::MMID_MEDIUM);
        }

        public function getThumbnail() {
                return $this->getPath(MediaProcessor::MMID_THUMBNAIL);
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
                return $this->getData('hash') . '.' . $this->getExtension();
                return self::getEncryptedFilename($this->getData('hash')) . '.' . $this->getExtension();
        }

        /**
         * Get media specific folder based on id and hash 
         * existing inside the media archive. Without filename.
         * @return type
         */
        public function getFolder($relative = true) {
                $id = $this->getData('media_id');
                $folder = $id . '/';
                if ($relative)
                        return FOLDER_MEDIA_REL . self::getDatabaseFolder() . $folder;
                return FOLDER_MEDIA . self::getDatabaseFolder() . $folder;
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
                return URL . 'media/' . $this->getData('hash');
                // Might override to distinct between album etc
                return $this->getFolder(false) . $this->getFilename();
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