<?php

/**
 * Description of ControllerPageView
 * @author merten
 */
class ControllerTestView extends ControllerView {

        public function __construct() {
                parent::__construct();
        }

        public function addTestDemo() {

                $objImg = Media::get(array(
                            'mediatype' => 'image/jpg',
                            'extension' => 'jpg',
                            'media_id' => 1,
                            'hash' => '002'
                        ));

                $body = $this->getBody();
                $div = new HtmlDiv();
                $div
                        ->addClass('x')
                        ->addClass('href')
                        ->addData('type', 'test')
                        ->addData('vars', '123')
                //->addData('confirm', 'dontknow')
                ;
                
                $image = new HtmlImg();
                $image->setSrc( $objImg->getThumbnail());
                $div->addChild( $image );
                
                $div->content = "Hi, i've got an action attached to me that will run when clicked. The action will communicate straight to the xhr controller of origin and return a bogus message with some js and css assets that will be loaded, and bound with the response view.";
                $body->addChild($div);
        }

}

?>