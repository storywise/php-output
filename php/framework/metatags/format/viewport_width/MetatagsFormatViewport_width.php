<?php

/**
 * Description of MetatagsFormatViewport_width
 * @author merten
 */
class MetatagsFormatViewport_width extends MetatagsFormat {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function getDefault() {
                return 'device-width';
        }

}

?>