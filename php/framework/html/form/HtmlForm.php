<?php

class HtmlForm extends HtmlAbstract {

        public function __construct() {
                parent::__construct('form');
        }
        
        public function setAction( $action ) {
                $this->setProperty('action', $action );
                return $this;
        }
}

?>