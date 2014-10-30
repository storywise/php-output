<?php

/**
 * Description of InstanceArgs
 * @author merten
 */
class InstanceArgs {

        protected $args = array();

        public function __construct() {
                
        }

        public function debug() {
                xx($this->args);
        }

        public function __call($func, $arguments) {
                $isSet = fnmatch('set*', $func);
                if ($isSet) {
                        if (!isset($arguments[0]))
                                return false;
                        // Assign value that has just been set with setter
                        $varRequest = strtolower(ltrim($func, 'set'));
                        $this->args[$varRequest] = $arguments[0];
                        return true;
                }

                $isGet = fnmatch('get*', $func);
                if ($isGet) {
                        // Return value that has been requested with getter
                        $varRequest = strtolower(ltrim($func, 'get'));

                        if (isset($this->args[$varRequest]))
                                return $this->args[$varRequest];
                }

                if (count($arguments) > 0) {
                        // Future, can add type checking above prior to returning the value T_ARRAY, T_STRING, T_INT etc
                        $doExpect = isset($arguments[0]) && $arguments[0] === true;
                        if ($doExpect) {
                                throw new Exception("Expected variable `$varRequest` to be set in InstanceArgs.");
                        }
                }
                return false;
        }

}

?>