<?php

/**
 * Description of ControllerPage
 * @author merten
 */
class ControllerPage extends Controller {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {

                // Load page assets
                OutputAssets::get()->addAssetsByTemplate('page');
                OutputAssets::get()->addJavascriptVars(array(
                    'test' => true
                ));

                $this->getView()->addDemo();
                
                $mt = new Metatags(array());

                // Write the head with meta-tags
                $this->getView()->head($mt);
        }

}

?>