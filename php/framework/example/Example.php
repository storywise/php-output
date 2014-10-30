<?php

/**
 * Description of Myclass
 * @author merten
 */
class Example extends Instance {

        public static function get() {
                $instance = Instance::create( array('example'));
                return $instance;
        }
        
        public static function get2( $hotelType ) {
                $instance = Instance::create( array('example'), array('type', $hotelType));
                return $instance;
        }
        
        public static function get3() {
                $instance = Instance::create( array('example'), false, Instance::$SINGLETON, Instance::$AUTH );
                return $instance;
        }
        
        /* Instance */
        
        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct( $model, $view );
        }
        
        public function setContext( $context ) {
                $this->getArgs()->setContext( $context );
        }
        
        public function output() {
                $data = $this->getModel()->getData();
                $view = $this->getView()->getRoomWithAViewAt( $data );
                echo $view;
        }

}

?>