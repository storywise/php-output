<?php

class HtmlInput extends HtmlAbstract {

        public function __construct($type = false) {
                parent::__construct('input');
                if ($type !== false)
                        $this->setType($type);
                $this->setClosingTag(false);
        }

        public function setReadOnly($on = true) {
                $val = ($on) ? 'true' : 'false';
                $this->setProperty('readonly', $val);
                return $this;
        }

        public function setPlaceholder($str) {
                $this->addProperty('placeholder', $str);
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

        public function setValue($value) {
                $this->addProperty('value', $value);
                return $this;
        }

        public function setType($type) {
                $types = array('text', 'checkbox', 'radio', 'date', 'password', 'hidden','email', 'color');
                if (!in_array($type, $types))
                        throw new Exception("Unknown input type: $type");
                $this->addProperty('type', $type);
                return $this;
        }

}

?>