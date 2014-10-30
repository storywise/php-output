<?php

/**
 * Description of MyclassView
 * @author merten
 */
class ExampleView extends InstanceView {

        public function __construct() {
                parent::__construct();
        }

        protected function prepare($string) {
                $context = $this->getArgs()->getContext();
                return "<div style=\"margin-bottom:15px;\">{$string}<br>{$context}\n</div>\n";
        }

        public function getRoomWithAViewAt($data) {
                return $this->prepare('The <b>' . $data['hotel'] . '</b>');
        }

}

?>