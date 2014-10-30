<?php

/**
 * Description of InstanceView
 * @author merten
 */
abstract class InstanceView implements InstanceArgsInterface {

        protected $args;

        function __construct() {}

        public function getArgs() {
                return $this->args;
        }
        
        public function setArgs(InstanceArgs &$args) {
                $this->args = $args;
        }
}

?>