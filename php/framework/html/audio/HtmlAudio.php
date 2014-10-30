<?php

/**
 * Description of HtmlAudio
 * @author merten
 */
class HtmlAudio extends HtmlAbstract {

        public function __construct() {
                parent::__construct('audio');
        }
        
        public function setControls() {
                $this->setProperty('controls', 'true');
                return $this;
        }

}

?>