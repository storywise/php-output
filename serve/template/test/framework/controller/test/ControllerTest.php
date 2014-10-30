<?php

/**
 * Description of ControllerPage
 * @author merten
 */
class ControllerTest extends Controller {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {

                // Load page assets
                OutputAssets::get()->addAssetsByTemplate('test');
                OutputAssets::get()->addSharedPackage('jquery_211', true);
                OutputAssets::get()->addJavascriptVars(array(
                    'foo' => 'bar'
                ));

                $this->getView()->addTestDemo();

                $mt = new Metatags(array());
                $this->getView()->head($mt);
        }

}

?>