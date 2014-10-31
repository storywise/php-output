<?php

/**
 * Description of MediaAudio
 * @author merten
 */
class MediaAudio extends Media {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function getPath( $modifierId = false) {
                return URL . 'media/' . $this->getData('hash');
        }

}

?>