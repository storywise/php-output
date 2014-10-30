<?php

class HtmlOption extends HtmlAbstract {

        public function __construct() {
                parent::__construct('option');
        }
        
        public function setValue( $value ) {
                $this->addProperty('value', $value );
        }
        
        public function selected() {
                $this->addProperty('selected', 'selected');
        }
}

?>