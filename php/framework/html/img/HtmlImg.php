<?php

/**
 * Description of HtmlImg
 * @author merten
 */
class HtmlImg extends HtmlAbstract {

        public function __construct() {
                parent::__construct('img');
        }
        
        public function setSrc( $path ) {
                $this->addProperty('src', $path );
                return $this;
        }
}

?>