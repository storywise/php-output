<?php

/**
 * Description of ExampleTypeAffordableModel
 * @author merten
 */
class ExampleTypeAffordableModel extends ExampleModel {

        public function __construct() {
                parent::__construct();
        }

        public function getData() {
                return array('hotel' => 'Sunnydrive-Inn');
        }

}

?>