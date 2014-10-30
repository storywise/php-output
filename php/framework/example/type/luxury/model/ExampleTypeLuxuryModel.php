<?php

/**
 * Description of ExampleTypeAffordableModel
 * @author merten
 */
class ExampleTypeLuxuryModel extends ExampleModel {

        public function __construct() {
                parent::__construct();
        }

        public function getData() {
                return array('hotel' => 'Oceanview-Inn');
        }

}

?>