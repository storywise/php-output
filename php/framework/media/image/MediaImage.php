<?php

/**
 * Description of MediaImage
 * @author merten
 */
class MediaImage extends Media {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function getPath($modifierId = false) {
                $modified = $this->prepareModified($modifierId, false);
                if ($modified !== false)
                        return $modified;
                return parent::getPath();
        }

        public function getPathRelative($modifierId = false) {
                $modified = $this->prepareModified($modifierId);
                if ($modified !== false)
                        return $modified;
                return parent::getPathRelative();
        }

        protected function prepareModified($modifier, $getRelative = true) {

                Bench::mark("Media.prepareModified");

                if ($modifier !== false) {

                        // Where should the modified exist?
                        if (!$this->hasModifiedImage($modifier)) {
                                // Create the actual modified image
                                $this->createModified($modifier);
                        }

                        $pathModified = $this->getModifiedPath($modifier, true, $getRelative);
                        Bench::mark("Media.prepareModified ready");

                        return $pathModified;
                }
                return false;
        }

        protected function createModified($modifierConfig) {
                Bench::mark("Media.createModified");
                $hasModifier = is_array($modifierConfig);
                if (!$hasModifier)
                        return false;

                // Get the path where the modified file should be, relatively accessible with filesystem
                $modifiedFile = $this->getModifiedPath($modifierConfig);

                // Create instance of the media processor, passing in config and extension
                $modifier = MediaProcessor::get($modifierConfig, $this->getExtension());

                // Set current media item to modify
                $modifier->setMedia($this);

                // Set path to save new image to
                $modifier->setTargetPath($modifiedFile);

                $modified = $modifier->modify();

                Bench::mark("Media.createModified ($modified) finished");

                // Get on with it
                if ($modified === false)
                        return false;

                return true;
        }

        /**
         * Specific for gif at the moment, the rest doesnt animate
         * 
         * @return boolean
         */
        public function isAnimated() {
                return false;
        }

        /**
         * Default compression for all items.
         * Gifs/Pngs might have different settings
         * @return int
         */
        public function getCompression() {
                return 80;
        }

        /**
         * Return path of modified media item, 
         * taking shared media folder into consideration
         * @param type $modifierId
         * @return string
         */
        public function hasModifiedImage($config) {
                //$this->getFolder() Removed
                $supposedFile = $this->getModifiedPath($config);
                $isExist = file_exists($supposedFile);
                return $isExist;
        }

        /**
         * Get path to folder or path to file of modified version.
         * Doesnt mean it exists or not, its just the target path where the file
         * is, or will be.
         * 
         * @param type $modifierId
         * @param type $appendFilename
         * @return type
         */
        public function getModifiedPath($config, $appendFilename = true, $relative = true) {

                if (!is_array($config))
                        throw new Exception("Expected array, got $config");

                // Requested modification
                $id = $config['mediamodifier_id'];

                // Get folder where all modified material is stored into, to be deleted at will
                $modifiedFolder = $this->getModifiedFolder($relative);

                // Encode the modification id into a base64 encrypted folder storing all images with that specific modification
                $mmFolderName = base64_encode($id * BASE64_ID_SEED) . '/';
                // Create weird looking base64 modifier specific folder in group folder
                $mmFolderPath = $modifiedFolder . $mmFolderName;

                // Meanwhile do a check that the media folder is actually present with our source files
                if (!is_dir($this->getFolder()))
                        throw new Exception('Requested image resource does not exist, this happens in database migrations where an image link from the previous database does not have a file entry in the media catalog. Please copy the previous media catalog along, or clear the media table.' . $this->getFolder());

                // Create dir if not exists
                if (!is_dir($mmFolderPath)) {
                        mkdir($mmFolderPath);
                }

                // Return location or target of the modified image
                return $appendFilename ? $mmFolderPath . $this->getModifiedFilename() : $mmFolderPath;
        }

        public function getModifiedFolder($relative = true) {

                $folder = '_m/';
                $target = FOLDER_MEDIA_ARCHIVE . $folder;

                if (!is_dir($target))
                        mkdir($target);
                return $target;
        }

        public function getModifiedFilename() {
                return base64_encode($this->getData('id') * BASE64_ID_SEED) . '.' . $this->getExtension();
        }

        public function getSize() {
                return getimagesize($this->getPathRelative());
        }

        public function isDisplayable() {
                return true;
        }

}

?>