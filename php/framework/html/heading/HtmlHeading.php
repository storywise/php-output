<?php

class HtmlHeading extends HtmlAbstract {
        public function __construct( $num ) {
                $num = $num > 7 || $num < 0 ? 2 : $num;
                parent::__construct('h'.$num);
        }
}
?>
