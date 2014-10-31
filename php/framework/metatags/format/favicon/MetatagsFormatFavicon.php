<?php

/**
 * Description of MetatagsFormatFavicon
 * @author merten
 */
class MetatagsFormatFavicon extends MetatagsFormat {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }
        
        public function getDefault() {
                return 'favicon.ico';
        }
}

?>