<?php

/**
 * Description of ExampleModel
 * @author merten
 */
class ExampleModel extends InstanceModel {

        public function __construct() {
                parent::__construct();
        }

        public function getData() {
                return array('hotel' => 'PrettyAverage-Inn');
        }

}

?>