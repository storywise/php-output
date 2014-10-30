<?php

/**
 * Description of HtmlSource
 * @author merten
 */
class HtmlSource extends HtmlAbstract {

        public function __construct() {
                parent::__construct('source');
        }
        
        public function setSrc( $src ) {
                $this->setProperty('src', $src );
                return $this;
        }
        
        public function setType( $type ) {
                $this->setProperty('type', $type );
                return $this;
        }
        
        

}

?>