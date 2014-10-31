<?php

include_once ROOT . 'php/vendor/wideimage/WideImage.php';

abstract class MediaProcessor extends Instance {

        const MMID_MEDIUM = 'medium';
        const MMID_THUMBNAIL = 'small';
        const MMID_WATERMARK = 'watermark';

        public static function get($config, $extension) {
                $settings = new AppSettings($config['config']);

                // Default settings, overwritten by modifier config possibly
                $settings
                        ->setDefault('mediamodifier_id', $config['mediamodifier_id'])
                        // Unless overwritten
                        ->setDefault('width', '800')
                        // Unless overwritten
                        ->setDefault('height', '800')
                        // Unless overwritten
                        ->setDefault('wmposx', 'top')
                        // Unless overwritten
                        ->setDefault('wmposy', 'left')
                        // Unless overwritten
                        ->setDefault('fit', 'inside')
                        // Unless overwritten
                        ->setDefault('watermark', false)
                ;

                // Check if we are going to modify with a watermark
                $requestWatermark = $settings->get('watermark') !== false;
                if ($requestWatermark) {

                        $hasWatermark = file_exists($settings->get('watermark'));
                        if (!$hasWatermark) {
                                $settings->set('watermark', ROOT . $settings->get('watermark'));
                                // Last chance to rectify a missing watermark by checking the root in shared mode
                                $hasWatermark = file_exists($settings->get('watermark'));
                        }
                        $isWatermark = $hasWatermark;
                } else
                        $isWatermark = false;

                // Currently we are supporting just watermark and resizing modifiers, this could grow
                // Making it subject to change once more
                $generalType = $isWatermark ? 'watermark' : 'resize';

                // Preceeded by Processor to form MediaProcessorWatermark for example
                $defaultSubAbstractType = 'processor';

                // Run along these types and fetch what is there
                $types = array($defaultSubAbstractType, $generalType, $extension);

                $obj = Instance::create('Media', $types, Instance::$NOVIEW, Instance::$NOABSTRACT);
                if ($obj !== false) {
                        $obj->setModifierConfig($config);
                        $obj->setModifierSettings($settings);
                        return $obj;
                }

                return false;
        }

        protected $config;
        protected $settings;
        protected $outputMedia;
        protected $media;
        protected $targetPath;

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        protected function modifyText( &$image ) {
                $modType = $this->getArgs()->get__types()[1];
                $textApplier = MediaText::get($modType);

                if ($textApplier !== false) {
                        $textApplier->setWideImage($image);
                        $textApplier->setMedia($this->media);
                        $textApplier->setSettings($this->getModifierSettings());
                        $textApplier->modify();
                }
        }

        /**
         * 
         * @param AppSettings $settings
         */
        public function setModifierSettings($settings) {
                $this->settings = $settings;
        }

        protected function getModifierSettings() {
                return $this->settings;
        }

        public function setModifierConfig($config) {
                $this->config = $config;
        }

        protected function getModifierConfig() {
                return $this->config;
        }

        public function setMedia(Media $media) {
                $this->media = $media;
        }

        protected function getTargetPath() {
                return $this->targetPath;
        }

        public function setTargetPath($path) {
                $this->targetPath = $path;
        }

        public function modify() {
                return $this->targetPath;
        }

}

?>
