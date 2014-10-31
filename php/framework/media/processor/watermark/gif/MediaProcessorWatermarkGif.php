<?php

include_once ROOT . 'php/vendor/gif/GIFDecoder.php';
include_once ROOT . 'php/vendor/gif/GIFEncoder.php';

class MediaProcessorWatermarkGif extends MediaProcessorWatermark {

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
xx('modify watermark gif');
                // If not animated, then regular applying of watermark is fine
                if (!$this->media->isAnimated()) {
                        return parent::modify($this->getTargetPath());
                }
                
                // We skip the watermark in animated gifs for now
                $resize = MediaProcessor::get( $this->getModifierConfig(), $this->media->getExtension());
                $resize->setMedia( $this->media );
                $resize->setTargetPath( $this->getTargetPath() );
xx($resize);
                return $resize->modify();
        }
}

?>
