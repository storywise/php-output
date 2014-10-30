<?php

/**
 * Description of ControllerPageView
 * @author merten
 */
class ControllerPageView extends ControllerView {

        public function __construct() {
                parent::__construct();
        }

        public function addDemo() {

                $body = $this->getBody();

                $div = new HtmlDiv();
                $div->addClass('demo');
                $div->content = "Hello world from the page controller.";

                $a = new HtmlAnchor();
                $a
                        ->setHREF(URL . 'test')
                        ->setContent('Click to visit the test template')
                ;
                $div->addChild($a);

                $body->addChild($div);
        }

}

?>