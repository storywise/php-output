<?php

/**
 * Description of ExampleTypeAffordableView
 * @author merten
 */
class ExampleTypeAffordableView extends ExampleView {

        public function __construct() {
                parent::__construct();
        }
        
       public function getRoomWithAViewAt($data) {
                return $this->prepare('The affordable <i>' . $data['hotel'] . '</i>');
        }

}

?>