<?php

/**
 * Description of MediaText
 * @author merten
 */
class MediaText extends Instance {

        public static function get($modifier) {
                $inst = Instance::create(array('media', 'text'), array($modifier), Instance::$NOMODEL, Instance::$NOVIEW, Instance::$NOABSTRACT);
                return $inst;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function setSettings($settings) {
                $this->getArgs()->setSettings($settings);
        }

        public function setMedia($media) {
                $this->getArgs()->setMedia($media);
        }

        public function setWideImage(&$wideImage) {
                $this->getArgs()->setWideImage($wideImage);
        }

        public function modify() {
                
        }

}

?>