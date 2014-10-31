<?php

/**
 * Description of MetatagsFormatUrl
 * @author merten
 */
class MetatagsFormatUrl extends MetatagsFormat {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function getFormat() {
                return $this->getArgs()->getInput();
        }
        
        public function getDefault() {
                return OutputParam::getURI();//URL . rtrim(ltrim($_SERVER['REQUEST_URI'], '/'), '/');
        }

}

?>