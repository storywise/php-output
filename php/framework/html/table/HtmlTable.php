<?php

class HtmlTable extends HtmlAbstract {

        public function __construct() {
                parent::__construct('table');
        }

        public function setSpacing($num) {
                $this->setProperty('cellspacing', $num);
                return $this;
        }

        public function setPadding($num) {
                $this->setProperty('cellpadding', $num);
                return $this;
        }

}

?>