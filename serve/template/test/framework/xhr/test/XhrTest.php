<?php

/**
 * Description of XhrTest
 * @author merten
 */
class XhrTest extends Xhr {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function output() {

                $div = new HtmlDiv();
                $div
                        ->addClass('abra')
                        ->setContent("ABRACADABRA click me")
                ;

                $assets = OutputAssets::get();
                $assets->addTemplatePackage('test', 'woops');

                // Ask OutputAssets for required assets?
                $response = XhrResponse::get();
                $response
                        ->addHandler('abra')
                        ->addAsset($assets->getIncludes(true))
                        ->addJson(array('view' => $div->getOutput()))
                        ->output()
                ;
        }

}

?>