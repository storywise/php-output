<?php

abstract class Instance {

        const MODEL = 'Model';
        const VIEW = 'View';

        private static $singleton = array();
        private static $cache = array();
        private static $debugging = false;

        /**
         * For the sake of the demo, allow for debug messages
         * @param boolean $on
         */
        public static function setDebugging($on = true) {
                self::$debugging = $on;
        }

        private static function getExistingTypeByList($types) {

                // Create signature of types as one string
                $signature = implode($types);

                // Check if this type request has previously delivered an existing class
                if (isset(self::$cache[$signature]))
                        return self::$cache[$signature];

                // This starts with the full type i.e. ImageJpeg, and second just Image as a general type
                $numTypes = count($types);
                for ($i = 0; $i < $numTypes; $i++) {

                        // Type of Class
                        $specificType = $types[$i];

                        // Get custom class
                        if (class_exists($specificType, true)) {

                                // Cache the found type for a potential next use without
                                // stressing the autoloader with unnecesssary requests
                                self::$cache[$signature] = $specificType;

                                return $specificType;
                        }
                }
                return false;
        }

        private static function getExistingClassName($types, $abstract, $extendAbstact = false, $options = false) {

                // List to collect types in
                $arr = array();

                // Stacking of types upon each other in this string
                $str = '';

                array_unshift($types, $abstract);

                $isExtendAbstract = $extendAbstact !== false;
                $numTypes = count($types);

                for ($i = 0; $i < $numTypes; $i++) {
                        $type = $types[$i];

                        // If for some reason a false parameter came in, skip it
                        if ($type === false)
                                continue;

                        // Append type to current string ( already class camelcase conform string )
                        $str .= $type;

                        // Decide the string matching type
                        $strType = ($isExtendAbstract) ? $str . $extendAbstact : $str;

                        // Should we scan for the entire range within the chain?
                        // Or stick to the last possible combination?
                        // For example OneTwoThree or also allow One, OneTwo, and OneTwoThree
                        $doChain = $options[self::OPTION_CHAIN];

                        // If we should not chain and encounter the last option as mentioned above
                        $isFirstNoChainItt = (!$doChain && $i == $numTypes - 1);

                        // Allow types to be gathered when meeting these criteria
                        $allowTypeAdd = $doChain || $isFirstNoChainItt;

                        if ($allowTypeAdd) {

                                // Check the skip abstract option, which never returns just the abtstract
                                // For example OneTwoThree will never return One. Can also be enforced
                                // by the `abstract class` keyword in PHP
                                $doSkipAbstract = !$options[self::OPTION_ABSTRACT] && $str == $abstract;

                                if (!$doSkipAbstract)
                                        array_unshift($arr, $strType);

                                // Additionally if authorized type is required, add all avaiable auth types too:
                                if ($options[self::OPTION_AUTH]) {
                                        if ($doSkipAbstract)
                                                continue;
                                        $auths = self::$USER_AUTH_TYPES;
                                        for ($a = 0; $a < count($auths); $a++) {
                                                $xstr = ToolsString::cls($auths[$a]);
                                                $strType = ($isExtendAbstract) ? $str . $xstr . $extendAbstact : $str . $xstr;
                                                array_unshift($arr, $strType);
                                        }
                                }
                        }
                        if ($isFirstNoChainItt)
                                break;
                }

                /*echo '<div style="margin-bottom:15px;">' .
                'Possible instance list:' .
                '<pre>' .
                print_r($arr, true) .
                '</pre>' .
                '</div>';*/

                return self::getExistingTypeByList($arr);
        }

        public static $USER_AUTH_TYPES = array();

        public static function setAuthTypes($arr) {
                self::$USER_AUTH_TYPES = $arr;
        }

        const OPTION_VIEW = 'view';
        const OPTION_MODEL = 'model';
        const OPTION_CHAIN = 'chain';
        const OPTION_SINGLETON = 'singleton';
        const OPTION_AUTH = 'auth';
        const OPTION_ABSTRACT = 'abstract';

        static $NOVIEW = array(self::OPTION_VIEW, false);
        static $NOMODEL = array(self::OPTION_MODEL, false);
        static $NOCHAIN = array(self::OPTION_CHAIN, false);
        static $SINGLETON = array(self::OPTION_SINGLETON, true);
        static $AUTH = array(self::OPTION_AUTH, true);
        static $NOABSTRACT = array(self::OPTION_ABSTRACT, false);

        /**
         * 
         * @param type $abstract
         * @param type $types
         * @return boolean|\Instance
         * @throws Exception
         */
        public static function create($abstract, $types = false) {

                // Put abstract in an array if string
                if (is_string($abstract))
                        $abstract = array($abstract);

                // Create standards compliant string of abstract type(s)
                $abstract = ToolsString::cls($abstract);

                // Set default value if no types where defined/required
                $types = $types === null || $types === false || !is_array($types) ? array() : ToolsString::cls($types, true);

                // Setup the options for instance retrieval
                $options = array(
                    self::OPTION_VIEW => true,
                    self::OPTION_MODEL => true,
                    self::OPTION_CHAIN => true,
                    self::OPTION_SINGLETON => false,
                    self::OPTION_AUTH => false,
                    self::OPTION_ABSTRACT => true
                );

                // Override default options with requested options
                $numargs = func_num_args();
                if ($numargs > 2) {
                        $arg_list = func_get_args();
                        for ($i = 2; $i < $numargs; $i++) {
                                $option = (array) $arg_list[$i];
                                $options[$option[0]] = $option[1];
                        }
                }

                // Get controller, defaults to CMS
                $strControllerType = self::getExistingClassName($types, $abstract, false, $options);

                // Did we find an existing classname ?
                if ($strControllerType !== false) {

                        // Check if it has ever been executed as a singleton item
                        // This does not require the singleton option again if it has been run once.
                        // Otherwise its pointless to have the option to have both a singleton instance 
                        // and still have the ability to instantiate a 'plain' instance, right?
                        if (isset(self::$singleton[$strControllerType])) {
                                return self::$singleton[$strControllerType];
                        }

                        // Create model if exists
                        $model = null;
                        if ($options[self::OPTION_MODEL]) {
                                $strModelType = self::getExistingClassName($types, $abstract, Instance::MODEL, $options);
                                if ($strModelType !== false)
                                        $model = new $strModelType();
                        }

                        // Create view if exists, and if required
                        $view = null;
                        if ($options[self::OPTION_VIEW]) {
                                $strViewType = self::getExistingClassName($types, $abstract, Instance::VIEW, $options);
                                if ($strViewType !== false)
                                        $view = new $strViewType();
                        }

                        // Create controller
                        $obj = new $strControllerType($model, $view);
                        if (!($obj instanceof Instance))
                                throw new Exception('Created instance does not inherit \'Instance\'');
                        $obj->setTypes($types);


                        // TODO NO CHAIN should check if the class is not types-1 
                        if (!$options[self::OPTION_CHAIN] && get_class($obj) == $abstract) {
                                return false;
                        }

                        if ($options[self::OPTION_SINGLETON] && !isset(self::$singleton[$strControllerType]))
                                self::$singleton[$strControllerType] = $obj;

                        return $obj;
                } else {
                        return false;
                }
        }

        protected $__model;
        protected $__view;
        protected $__types;
        protected $__table;
        protected $args;
        private static $cacheVars;

        public function __construct(DbConnect $model = null, $view = null) {
                if ($view instanceof InstanceArgsInterface)
                        $view->setArgs($this->getArgs());
                if ($model instanceof InstanceArgsInterface)
                        $model->setArgs($this->getArgs());

                $this->__model = $model;
                $this->__view = $view;
        }

        /**
         * Get the argument manager
         * @return InstanceArgs
         */
        protected function &getArgs() {
                if (!isset($this->args))
                        $this->args = new InstanceArgs();
                return $this->args;
        }

        protected function &getCache() {
                $signature = implode($this->getArgs()->get__types());
                if (!isset(self::$cacheVars[$signature]))
                        self::$cacheVars[$signature] = new InstanceCache();
                return self::$cacheVars[$signature];
        }

        public function getTable() {
                return $this->__table;
        }

        public function setTable($table) {
                $this->__table = $table;
                $this->getArgs()->setTable($table);
                return $this;
        }

        public function hasView() {
                return isset($this->__view);
        }

        public function hasModel() {
                return isset($this->__model);
        }

        public function getView() {
                if (!isset($this->__view))
                        throw new Exception('No view available');
                return $this->__view;
        }

        public function getModel() {
                if (!isset($this->__model))
                        $this->__model = new DbConnect();
                return $this->__model;
        }

        public function setTypes($types) {
                $this->__types = $types;
                $this->getArgs()->set__types($types);
        }

}

?>
