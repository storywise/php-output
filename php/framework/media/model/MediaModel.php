<?php

/**
 * Description of MediaModel
 * @author merten
 */
class MediaModel extends DbConnect {

        public function __construct() {
                parent::__construct();
        }

        public function getModifierConfig($id) {

                $modifiers = array(
                    'small' => array(
                        'mediamodifier_id' => 1,
                        'config' => 'width=375,height=375,fit=inside'
                    ),
                    'medium' => array(
                        'mediamodifier_id' => 2,
                        'config' => 'width=750,height=750,fit=inside'
                    ),
                    'watermark' => array(
                        'mediamodifier_id' => 3,
                        'config' => 'watermark=static/brand/watermark.png,width=1600,height=1600,wmposx=right,wmposy=bottom'
                    )
                );

                return $modifiers[$id];
        }

}

?>