<?php

class HtmlButton extends HtmlAbstract {

        public function __construct() {
                parent::__construct('button');
        }

        public function disabled() {
                $this->addProperty('disabled', 'disabled');
                return $this;
        }

        public function setType($type) {
                $types = array('button', 'reset', 'submit');
                if (!in_array($type, $types))
                        throw new Exception("Unknown input type: $type");
                $this->addProperty('type', $type);
                return $this;
        }

}

?>