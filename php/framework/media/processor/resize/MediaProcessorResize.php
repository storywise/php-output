<?php

class MediaProcessorResize extends MediaProcessor {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        /**
         * 
         * @param type $w
         * @param type $h
         * @param type $saveTo
         */
        public function modify() {

                $settings = $this->getModifierSettings();

                $w = $settings->get('width');
                $h = $settings->get('height');

                $original = WideImage::load($this->media->getPathRelative());
                $thumb = $original->resize($w, $h, "inside");

                parent::modifyText($thumb);

                $thumb->saveToFile($this->getTargetPath(), $this->media->getCompression());

                return $this->getTargetPath();
        }

}

?>
