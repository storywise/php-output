<?php

/**
 * Description of ExampleTypeLuxury
 * @author merten
 */
class ExampleTypeLuxury extends Example {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct( $model, $view );
        }
        
        public function output() {
                $data = $this->getModel()->getData();
                $view = $this->getView()->getRoomWithAViewAt( $data );
                echo $view;
        }

}

?>