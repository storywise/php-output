<?php

/**
 * Description of Xhr
 * @author merten
 */
abstract class Xhr extends Instance {

        protected $controller;
        protected $data;

        public static function get($type) {
                $xhr = Instance::create('Xhr', array($type), Instance::$AUTH, Instance::$NOABSTRACT);
                if ($xhr !== false) {
                        // Get matching controller for xhr request
                        $controller = Controller::get($type);

                        // Set matching controller or false if none
                        $xhr->setController($controller);
                }
                return $xhr;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function setData($data) {
                $this->data = $data;
        }

        protected function getController() {
                return $this->controller;
        }

        public function setController($controller) {
                $this->controller = $controller;
        }

        public function prepare() {
                
        }

        public function output() {
                $response = XhrResponse::get();
                $response
                        ->addError('Type did not implement a custom output')
                        ->output()
                ;
        }

}

?>