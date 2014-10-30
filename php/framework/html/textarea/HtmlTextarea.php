<?php

class HtmlTextarea extends HtmlAbstract {

        public function __construct() {
                parent::__construct('textarea');
        }

        // Could share with HtmlInput
        public function setReadOnly($on = true) {
                $val = ($on) ? 'true' : 'false';
                $this->setProperty('readonly', $val);
                return $this;
        }

        public function setMaxlength($max) {
                $this->addProperty('maxlength', $max);
                return $this;
        }

        public function setName($name) {
                $this->addProperty('name', $name);
                return $this;
        }

        public function disable() {
                $this->addProperty('disabled', 'disabled');
                return $this;
        }

}

?>