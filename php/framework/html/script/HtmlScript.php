<?php

/**
 * Description of HtmlScript
 * @author merten
 */
class HtmlScript extends HtmlAbstract {

        public function __construct() {
                parent::__construct('script');
        }
        
        public function setPhp() {
                $this->setProperty('type', 'text/php');
        }
        
        public function setJavascript() {
                $this->setProperty('type', 'text/javascript');
        }

}

?>