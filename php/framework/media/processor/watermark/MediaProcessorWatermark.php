<?php

class MediaProcessorWatermark extends MediaProcessor {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function modify() {

                $settings = $this->getModifierSettings();
                $original = $original = WideImage::load($this->media->getPathRelative());

                $oriWidth = $original->getWidth();
                $oriHeight = $original->getHeight();

                // Check relative in profile
                $pathWm = $settings->get('watermark');

                // If it doesnt exist use the root to check the primary profile's watermark
                if (!file_exists($pathWm))
                        $pathWm = ROOT . $pathWm;

                // Fit image based on watermark/overlay
                if ($settings->get('fit') == 'watermark') {

                        $ox = $settings->get('wmposx');
                        $oy = $settings->get('wmposy');
                        $watermark = WideImage::load($pathWm);
                        $w = $watermark->getWidth();
                        $h = $watermark->getHeight();

                        $img = $original
                                ->resize($settings->get('width'), $settings->get('height'), 'outside')
                                ->resizeCanvas($w, $h, $ox, $oy)
                                ->merge($watermark, 0, 0, 100)
                        ;

                        $new = $img;
                } else {

                        // Fit watermark based on image
                        // Resize according to plugin settings
                        if ($settings->get('width') < $oriWidth && $settings->get('height') < $oriHeight)
                                $img = $original->resize($settings->get('width'), $settings->get('height'), $settings->get('fit'));
                        else
                                $img = $original;
                        
                        // Only when fit outside and 
                        if ($settings->get('fit') == 'outside') {
                                if ($img->getHeight() > $settings->get('height')) {
                                        $img = $img
                                                ->crop('center', 'center', $settings->get('width'), $settings->get('height'));
                                } else if ($img->getWidth() > $settings->get('width')) {
                                        $img = $img
                                                ->crop('center', 'center', $settings->get('width'), $settings->get('height'));
                                }
                        }

                        $watermark = WideImage::load($pathWm);
                        //$croppedWatermark = $watermark->crop('center', 'center', $img->getWidth(), $img->getHeight());

                        $new = $img->merge($watermark, $settings->get('wmposx'), $settings->get('wmposy'), 100);
                }

                parent::modifyText($new);

                $new->saveToFile($this->getTargetPath(), $this->media->getCompression());

                return $this->getTargetPath();
        }

        /* # To messy round corners and offset. 
         * $img = $this->original
          ->resize($settings->get('width'), $settings->get('height'), 'outside')
          ->resizeCanvas("100%+$outerMarginW", "100%+$outerMarginH", $ox, $oy)
          //->roundCorners($round, (!$roundbg ? null : $roundbg), $roundsmooth)
          ->merge($watermark, $ox, $oy, 100); */

        /* # Not implemented rounded corners
         *  $round = $settings->get('round');
          $roundbg = $settings->get('roundbg');
          $roundsmooth = $settings->get('roundsmooth'); */
        /* # Offset x,y not required. 
         * $oxNumber = $settings->extractNumber($ox);
          $oyNumber = $settings->extractNumber($oy); */
}

?>
