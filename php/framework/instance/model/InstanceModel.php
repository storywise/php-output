<?php

/**
 * Description of InstanceModel
 * @author merten
 */
abstract class InstanceModel extends DbConnect implements InstanceArgsInterface {

        protected $args;
        
        function __construct() {
                parent::__construct();
        }

        public function getArgs() {
                return $this->args;
        }
        
        public function setArgs(InstanceArgs &$args) {
                $this->args = $args;
        }
}

?>