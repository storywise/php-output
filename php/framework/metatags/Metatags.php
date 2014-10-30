<?php

class Metatags {

        public $description = "";
        public $title = "";
        public $sitename = "";
        public $url = "";
        public $image;
        public $secret = false;
        public $customTags;
        public $favicon = "favicon.ico";
        
        function __construct($data) {
                if ($data === false)
                        return;

                $this->title = !empty($data['title']) ? $data['title'] : '';
                $this->description = !empty($data['description']) ? $data['description'] : '';
                $this->sitename = !empty($data['sitename']) ? $data['sitename'] : '';
                $this->url = rtrim(ltrim($_SERVER['REQUEST_URI'], '/'), '/');
                $this->image ='';

                $this->keywords = !empty($data['keywords']) ? $data['keywords'] : '';
        }

        /**
         * Add an entire meta tag for specific reasons like refresh etc.
         * @param type $tag
         */
        public function addCustomTag($tag) {
                if (!isset($this->customTags))
                        $this->customTags = array();
                array_push($this->customTags, $tag);
        }

        public function getCustomTags() {
                return $this->customTags;
        }

        public function hasCustomTags() {
                return ToolsArray::is($this->customTags);
        }

        /**
         * Set the title of the page in the corresponding Open Graph and meta tags.
         * Do mind the concatenating of the titles, creating a hierarchy like:
         * Homepage - Some page - Some subcontent
         * @param type $title
         */
        public function setTitle($title) {
                if (!empty($title))
                        $this->title .= ' &mdash; ' . ucfirst($title);
        }

        /**
         * Compose a description for the open graph and meta tags
         * @param type $desc
         */
        public function setDescription($desc) {
                $desc = trim($desc);
                if (!empty($desc) && $desc !== "") {
                        if (is_array($desc)) {
                                // When an array of multiple text elements is passed
                                $strDescription = '';
                                $max = count($desc);
                                for ($i = 0; $i < $max; $i++) {
                                        $entry = $desc[$i];
                                        $str = strip_tags($this->getClean($entry['narrativebody']));
                                        $chopped = strlen($str) > 64 ? substr($str, 0, 64) . '...' : $str;
                                        $strDescription .= $chopped;
                                        if ($i < $max - 1)
                                                $strDescription .= ' &mdash; ';
                                }
                                $this->description .= '&mdash; ' . $strDescription;
                        } else
                                $this->description = $this->getClean($desc);
                }
        }

        private function getClean($str) {
                return str_replace(array("\r", "\n", "\t"), '', $str);
        }

        public function getRobots() {
                if (!$this->secret)
                        return '<meta name="robots" content="index, follow">' . "\n";
                else if ($this->secret)
                        return '<meta name="robots" content="noindex, nofollow">' . "\n";
        }

        public function getDescription() {
                return strip_tags($this->description);
        }

}

?>
