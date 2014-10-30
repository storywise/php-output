<?php

/**
 * Description of InstanceCache
 * @author merten
 */
class InstanceCache {

        protected $args = array();

        public function __construct() {
                
        }

        public function __call($func, $arguments) {

                // Num arguments
                $max = count($arguments);

                // A has request may have 0 arguments
                $isHas = fnmatch('has*', $func);

                // Are we requesting status of availability?
                if ($isHas) {

                        // Argument passed, must be key value
                        $isKeyValue = $max === 1;

                        $varRequest = strtolower(ltrim($func, 'has'));

                        if ($isKeyValue)
                                return isset($this->args[$varRequest][$arguments[0]]);
                        else
                                return isset($this->args[$varRequest]);
                }

                // Are we setting a value?
                $isSet = fnmatch('set*', $func);

                if ($isSet) {

                        $isKeyValue = $max === 2;
                        $isValue = $max === 1;
                        $isError = !$isKeyValue && !$isValue;

                        if ($isError)
                                throw new Exception('InstanceCache->set* require a single value argument, or a 2 arguments $key, $value pair.');

                        // Assign value that has just been set with setter
                        $varRequest = strtolower(ltrim($func, 'set'));

                        // Store key value pair in array under varrequest
                        if ($isKeyValue) {

                                // Set key value list
                                if (!isset($this->args[$varRequest]))
                                        $this->args[$varRequest] = array();

                                // Validate to see if we can set into array
                                if (!is_array($this->args[$varRequest]))
                                        throw new Exception("Attempt to set a key,value pair into an occupied cache value ($varRequest)");

                                // Set it
                                $this->args[$varRequest][$arguments[0]] = $arguments[1];
                        } else
                        // Plain cache value
                                $this->args[$varRequest] = $arguments[0];
                        return true;
                }

                // Are we getting a value?
                $isGet = fnmatch('get*', $func);

                if ($isGet) {

                        $isKeyValue = $max === 1;

                        // Return value that has been requested with getter
                        $varRequest = strtolower(ltrim($func, 'get'));

                        // Return value accordingly
                        if ($isKeyValue) {
                                if (isset($this->args[$varRequest][$arguments[0]]))
                                        return $this->args[$varRequest][$arguments[0]];
                        } else if (isset($this->args[$varRequest]))
                                return $this->args[$varRequest];
                }

                return false;
        }

}

?>