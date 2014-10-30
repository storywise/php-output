<?php


http://localhost:8080/mhq_php/site/1bdaaeef3be4d2495ce81a38e67df68d
        
/**
 * Description of HtmlIframe
 * @author merten
 */
class HtmlIframe extends HtmlAbstract {

        public function __construct() {
                parent::__construct('iframe');
        }

        public function setSrc($src) {
                $this->setProperty('src', $src);
                return $this;
        }

}

?>