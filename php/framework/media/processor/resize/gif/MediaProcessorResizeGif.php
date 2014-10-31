<?php

include_once ROOT . 'php/vendor/gif/GIFDecoder.php';
include_once ROOT . 'php/vendor/gif/GIFEncoder.php';

class MediaProcessorResizeGif extends MediaProcessorResize {

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        /**
         * 
         * @param type $w
         * @param type $h
         * @param type $saveTo
         */
        public function modify() {

                // If not animated, then regular resize is fine
                if (!$this->media->isAnimated()) {
                        return parent::modify();
                }

                // Okay, it seems we have an animated gif at our hands.
                // This will be a bit more complicated, extract frames, resize them and stick gif back together:

                $settings = $this->getModifierSettings();

                $w = $settings->get('width');
                $h = $settings->get('height');

                // The original animation which we need to resize
                $animation = $this->media->getPathRelative();

                @$gifDecoder = new GIFDecoder(fread(fopen($animation, "rb"), filesize($animation)));


                // Temporary folder to place resized frames in
                $tmpFolder = $this->media->getModifiedPath( $this->getModifierConfig(), false) . "__gifresize/";

                if (!is_dir($tmpFolder))
                        mkdir($tmpFolder);

                $tmpFileFolder = $tmpFolder . $this->media->getData('id') . '/';

                // Create if needed
                if (!is_dir($tmpFileFolder))
                        mkdir($tmpFileFolder);

                $prependFrame = 'frame_';

                $itt = 1;
                foreach ($gifDecoder->GIFGetFrames() as $frame) {

                        $f = $tmpFileFolder . "{$prependFrame}{$itt}.gif";
                        fwrite(fopen($f, "wb"), $frame);  // write each frame in a temporary file

                        $img = $f;
                        $newfilename = $f;

                        $returnedNewFileName = $this->resizeFrame($img, $w, $h, $newfilename);  // function to reduce the image

                        $itt++;
                }

//Build a frames array from sources
                $i = 1;
                $resizedFrames = array();
                if ($dh = opendir($tmpFileFolder)) {
                        while (false !== ( $dat = readdir($dh) )) {
                                if ($dat != "." && $dat != "..") {
                                        $f = $tmpFileFolder . "{$prependFrame}{$i}.gif";
                                        $resizedFrames [] = $f;
                                        $i++;
                                }
                        }
                        closedir($dh);
                }
                if (count($resizedFrames) > 0) {
                        $delay = $gifDecoder->GIFGetDelays();
                        $gifThumb = new GIFEncoder(
                                        $resizedFrames,
                                        $delay,
                                        0,
                                        2,
                                        0, 0, 0,
                                        "url"
                        );

                        $fpThumb = fopen($this->getTargetPath(), 'w');
                        fwrite($fpThumb, $gifThumb->GetAnimation());
                        fclose($fpThumb);

                        // Drop temporary stuff
                        ToolsFolder::removeDirWithContent($tmpFolder);
                        
                } else {
                        throw new Exception("No frames detected");
                }

                return $this->getTargetPath();
        }

        private function resizeFrame($img, $w, $h, $newfilename) {

                //Check if GD extension is loaded
                if (!extension_loaded('gd') && !extension_loaded('gd2')) {
                        trigger_error("GD is not loaded", E_USER_WARNING);
                        return false;
                }

                //Get Image size info
                $imgInfo = getimagesize($img);
                $im = imagecreatefromgif($img);

                // If image dimension is smaller, do not resize
                if ($imgInfo[0] <= $w && $imgInfo[1] * $h / $imgInfo[1]) {
                        $nWidth = $w;
                        $nHeight = $imgInfo[1] * ($w / $imgInfo[0]);
                } else {
                        $nWidth = $imgInfo[0] * ($h / $imgInfo[1]);
                        $nHeight = $h;
                }

                $nWidth = round($nWidth);
                $nHeight = round($nHeight);

                $newImg = imagecreatetruecolor($nWidth, $nHeight);
                imagealphablending($newImg, false);
                imagesavealpha($newImg, true);
                $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
                imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
                imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

                //Generate the file, and rename it to $newfilename
                imagegif($newImg, $newfilename);

                return $newfilename;
        }

}

?>
