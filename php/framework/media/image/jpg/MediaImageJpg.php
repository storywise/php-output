<?php

/**
 * Description of MediaImageJpg
 * @author merten
 */
class MediaImageJpg extends MediaImage {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }
        
        public function getCompression() {
                return 80;
        }

}

?>