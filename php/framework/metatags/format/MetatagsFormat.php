<?php

/**
 * Description of MetatagsFormat
 * @author merten
 */
class MetatagsFormat extends Instance {

        public static function get($type) {
                return Instance::create(array('metatags', 'format'), array($type), Instance::$NOMODEL, Instance::$NOVIEW, Instance::$NOABSTRACT);
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function setInput($input) {
                $this->getArgs()->setInput($input);
        }

        protected function sanitize($str) {
                return strip_tags(str_replace(array("\r", "\n", "\t"), '', $str));
        }

        public function getFormat() {
                return $this->sanitize($this->getArgs()->getInput(true));
        }
        
        public function getDefault() {
                return null;
        }

}

?>