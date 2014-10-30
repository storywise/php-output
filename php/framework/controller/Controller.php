<?php

class Controller extends Instance {

        /**
         * Return the active controller optionally by auth.
         * This allows developers to create a Controller extending the base one
         * specific to their profile, or/and specific to the user accessing it ( by authority )
         * 
         * @param string $folder
         * @return Controller
         */
        public static function get($type) {
                return Instance::create('Controller', array($type), Instance::$AUTH, Instance::$NOABSTRACT);
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);

                // Allow environment javascript to know where we are at
                OutputAssets::get()->addJavascriptVars(array(
                    'URL' => URL
                        )
                );
        }

        public function output() {
                $this->getView()->output();
        }

}

?>