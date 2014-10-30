<?php

/**
 * Description of HtmlB
 * @author merten
 */
class HtmlBold extends HtmlAbstract {

        public function __construct($text = false) {
                parent::__construct('b');
                if ($text !== false)
                        $this->addContent($text);
        }

}

?>