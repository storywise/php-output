<?php

/**
 * Description of MediaApplication
 * @author merten
 */
class MediaApplication extends Media {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function getPath($modifierId = false) {
                return URL . 'media/' . $this->getData('hash');
        }

}

?>