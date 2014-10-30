<?php

/**
 * Description of HtmlCenterv
 * @author merten
 */
class HtmlCenterv extends HtmlDiv {

        protected $inner;
        
        public function __construct() {
                parent::__construct();
                $this->addClass('centerv');
                $this->inner = new HtmlDiv();
                $this->inner->addClass('centervmid');
                parent::addChild( $this->inner );
        }

        public function addChild(HtmlAbstract $child) {
                $this->inner->addChild($child);
        }

        public function addContentAfter($content, $title = false) {
                return $this->inner->addContentAfter($content, $title);
        }

        public function setContentAfter($content, $title = false) {
                return $this->inner->setContentAfter($content, $title);
        }

        public function addContent($content, $title = false) {
                return $this->inner->addContent($content, $title);
        }

        public function setContent($content, $title = false) {
                return $this->inner->setContent($content, $title);
        }

}

?>