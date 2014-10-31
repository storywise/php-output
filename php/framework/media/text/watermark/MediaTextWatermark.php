<?php

/**
 * Description of MediaTextTcWatermark
 * @author merten
 */
class MediaTextWatermark extends MediaText {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function modify() {

                $image = $this->getArgs()->getWideImage();
                $media = $this->getArgs()->getMedia();

                $canvas = $image->getCanvas();
                $fontSize = 9;
                $font = ROOT . 'static/brand/fonts/myriad/myriadbold.ttf';
                $artist = $media->getData('credit');
                $grey = 20;

                if ($artist !== false) {
                        $name = $artist;
                        $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                        $canvas->writeText('left+3', 'bottom-2', $name);
                        $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                        $canvas->writeText('left+2', 'bottom-3', $name); // Shadow
                }

                /* $profileName = 'Test profile name'; //$profile->getData('profile');
                  $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                  $canvas->writeText('left+10', 'bottom-9', $profileName);
                  $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                  $canvas->writeText('left+9', 'bottom-10', $profileName); // Shadow

                  if (!empty($mediaId)) {
                  $mediaId = '#'.$mediaId;
                  $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                  $canvas->writeText('left+10', 'top+10', $mediaId);
                  $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                  $canvas->writeText('left+9', 'top+9', $mediaId);
                  } */

                return $image;
        }

}

?>