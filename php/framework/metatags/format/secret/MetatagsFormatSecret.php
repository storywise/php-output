<?php

/**
 * Description of MetatagsFormatSecret
 * @author merten
 */
class MetatagsFormatSecret extends MetatagsFormat {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }
        
        public function getFormat() {
                $isSecret = $this->getArgs()->getInput();
                if ($isSecret)
                        return 'noindex, nofollow';
                return 'index, follow';
        }
        
        public function getDefault() {
                return false;
        }
}


?>