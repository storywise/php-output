<?php

/**
 * Description of checkbox
 * @author merten
 */
class HtmlInputCheckbox extends HtmlInput {

        public function __construct() {
                parent::__construct('checkbox');
        }

        public function checked() {
                $this->setProperty('checked', 'checked');
                return $this;
        }

}

?>