<?php

/**
 * Description of MediaTextTerraResize
 * @author merten
 */
class MediaTextResize extends MediaText {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function modify() {

                $image = $this->getArgs()->getWideImage();
                $media = $this->getArgs()->getMedia();

                $canvas = $image->getCanvas();
                $fontSize = 9;
                $font = ROOT . 'static/brand/fonts/neutra/NeutraText-Book.otf';

                $mediaId = $media->getData('media_id');
                //$profile = Profile::get();
                $artist = 'Merten Snijders';
                
                $grey = 20;

                if (!empty($artist)) {

                        $name = $artist;
                        $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                        $canvas->writeText('left+3', 'bottom-12', $name);
                        $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                        $canvas->writeText('left+2', 'bottom-13', $name); // Shadow
                }

                $profileName = 'Test profilename'; //$profile->getData('profile');

                $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                $canvas->writeText('left+3', 'bottom-2', $profileName);
                $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                $canvas->writeText('left+2', 'bottom-3', $profileName); // Shadow

                if (!empty($mediaId)) {
                        $mediaId = '#' . $mediaId;
                        $canvas->useFont($font, $fontSize, $image->allocateColor($grey, $grey, $grey));
                        $canvas->writeText('left+3', 'top+2', $mediaId);
                        $canvas->useFont($font, $fontSize, $image->allocateColor(255, 255, 255));
                        $canvas->writeText('left+2', 'top+3', $mediaId); // Shadow        
                }

                return $image;
        }

}

?>