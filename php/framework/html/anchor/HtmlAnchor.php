<?php

class HtmlAnchor extends HtmlAbstract {
        public function __construct() {
                parent::__construct('a');
        }
        
        public function setTitle( $title ) {
                $this->addProperty('title', $title);
                return $this;
        }
        
        public function setHREF( $to ) {
                $this->addProperty('href', $to);
                return $this;
        }
        
        public function blank() {
                $this->addProperty('target', '_blank');
                return $this;
        }       
        
}
?>
