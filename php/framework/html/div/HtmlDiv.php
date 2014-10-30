<?php

class HtmlDiv extends HtmlAbstract {

        public function __construct() {
                parent::__construct('div');
        }
        
        public function setContenteditable() {
                $this->setProperty('contenteditable', 'true');
                return $this;
        }
}

?>