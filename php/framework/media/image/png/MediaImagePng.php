<?php

/**
 * Description of MediaImagePng
 * @author merten
 */
class MediaImagePng extends MediaImage {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }
        
        public function getCompression() {
                return 7;
        }

}

?>