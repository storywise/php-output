<?php

class Output extends Instance {

        const NOTFOUND = 'notfound';
        const FRONTEND = 'page';
        const BACKEND = 'xhr';

        // False unless configured right
        protected $controller = false;

        /**
         * @param type $type
         * @return Output
         */
        public static function get() {

                // Parse output parameters
                OutputParam::parse();

                // Automatically assume a page if it's not an xhr call
                $type = OutputParam::get(0) !== Output::BACKEND ? Output::FRONTEND : Output::BACKEND;

                // Create singleton output instance for this request
                $inst = Instance::create(array('output', 'type'), array($type), Instance::$SINGLETON, Instance::$NOABSTRACT);

                if ($inst === false)
                        throw new Exception("Required output type '$type' not found");

                // There we go
                $inst->output();

                return $inst;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {
                
        }
        
        public function output() {
                
                // Prepare for output
                $this->prepare();
                
                // Conduct output
                if ($this->controller !== false)
                        $this->controller->output();
        }

}

?>
