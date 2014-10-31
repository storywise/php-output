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

                $objImg = Media::get('static/archive/media/1/01.jpg');
                $objImg2 = Media::get('static/archive/media/1/02.jpg');
                $objImg3 = Media::get('static/archive/media/1/03.jpg');
                
                $body = $this->getBody();
                $div = new HtmlDiv();
                $div
                        ->addClass('x')
                        ->addClass('href')
                        ->addData('type', 'test')
                        ->addData('vars', '123')
                //->addData('confirm', 'dontknow')
                ;
                
                $objImg2->setCredit('Some credit for some artist');
                
                $image = new HtmlImg();
                $image->setSrc( $objImg->getPathModified( MediaProcessor::$SMALL ));
                $div->addChild( $image );
                
                $image2 = new HtmlImg();
                $image2->setSrc( $objImg2->getPathModified( MediaProcessor::$MEDIUM ));
                $div->addChild( $image2 );
                
                $image3 = new HtmlImg();
                $image3->setSrc( $objImg3->getPathModified( MediaProcessor::$WATERMARK ));
                $div->addChild( $image3 );
                
                $div->content = "Hi, i've got an action attached to me that will run when clicked. The action will communicate straight to the xhr controller of origin and return a bogus message with some js and css assets that will be loaded, and bound with the response view.";
                $body->addChild($div);
        }

}

?>