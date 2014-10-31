<?php

/**
 * Description of MediaImageGif
 * @author merten
 */
class MediaImageGif extends MediaImage {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        protected function prepareModified( $modifierId ) {
                $isWatermarkRequest = ( $modifierId == MediaProcessor::MMID_WATERMARK );
                
                // Skip modifying animated gifs when a watermark is needed
                // Have to investigate multi-frame watermarking
                if ($isWatermarkRequest && $this->isAnimated()) {
                        return false;
                }
                return parent::prepareModified( $modifierId );
        }
        
        public function getCompression() {
                return 7;
        }

        /**
         * Check if this is an animated image.
         * # Seems costly to do a file_get_contents with large files.
         * @credit ZeBadger php.net
         * @param type $filename
         * @return type
         */
        public function isAnimated() {

                $filecontents = file_get_contents($this->getPathRelative());
                $str_loc = 0;
                $count = 0;
                while ($count < 2) { # There is no point in continuing after we find a 2nd frame
                        $where1 = strpos($filecontents, "\x00\x21\xF9\x04", $str_loc);
                        if ($where1 === FALSE) {
                                break;
                        } else {
                                $str_loc = $where1 + 1;
                                $where2 = strpos($filecontents, "\x00\x2C", $str_loc);
                                if ($where2 === FALSE) {
                                        break;
                                } else {
                                        if ($where1 + 8 == $where2) {
                                                $count++;
                                        }
                                        $str_loc = $where2 + 1;
                                }
                        }
                }

                if ($count > 1) {
                        return(true);
                } else {
                        return(false);
                }
        }

}

?>