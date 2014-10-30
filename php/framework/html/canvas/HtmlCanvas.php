<?php

/**
 * Description of HtmlCanvas
 * @author merten
 */
class HtmlCanvas extends HtmlAbstract {

        public function __construct() {
                parent::__construct('canvas');
        }

        public function setWidth($w) {
                $this->setProperty('width', $w);
                return $this;
        }

        public function setHeight($h) {
                $this->setProperty('height', $h);
                return $this;
        }

}

?>