<?php

/**
 * Description of ControllerNotfound
 * @author merten
 */
class ControllerNotfound extends ControllerPage {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {

                // Load page assets
                OutputAssets::get()->addAssetsByTemplate('page');
                
                $body = $this->getView()->getBody();
                $div = new HtmlDiv();

                $div->content = "Sorry the requested URL does not exist.";
                $body->addChild($div);

                $mt = new Metatags(array());
                $this->getView()->head($mt);
        }

}

?>