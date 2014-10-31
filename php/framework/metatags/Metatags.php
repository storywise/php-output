<?php

class Metatags {

        const DESCRIPTION = 'description';
        const TYPE = 'type';
        const TITLE = 'title';
        const IMAGE = 'image';
        const KEYWORDS = 'keywords';
        const COPYRIGHT = 'copyright';
        const LANGUAGE = 'language';
        const URL = 'url';
        const SITENAME = 'sitename';
        const VIEWPORT_WIDTH = 'viewportwidth';
        const FAVICON = 'favicon';
        const SECRET = 'secret';

        protected $customTags;
        protected $data = array();

        function __construct($data) {

                // Set all properties
                foreach ($data as $key => $value) {
                        $this->setProperty($key, $value);
                }

                $list = array(
                    self::DESCRIPTION,
                    self::TYPE,
                    self::TITLE,
                    self::IMAGE,
                    self::KEYWORDS,
                    self::COPYRIGHT,
                    self::LANGUAGE,
                    self::URL,
                    self::SITENAME,
                    self::VIEWPORT_WIDTH,
                    self::FAVICON,
                    self::SECRET
                );

                // Scan through all available metatag properties to see whats missing
                foreach ($list as $key => $value) {
                        // If there is no value available in the input, try to obtain default
                        if (!isset($data[$value])) {

                                // See if there is a format object available for the given metatags property
                                $format = MetatagsFormat::get($value);

                                if ($format !== false) {

                                        // Get default value
                                        $defaultValue = $format->getDefault();

                                        // Set default value
                                        if ($defaultValue !== null)
                                                $this->setProperty($value, $defaultValue);
                                }
                        }
                }
        }

        public function setProperty($property, $value) {
                return $this->data[$property] = $value;
        }

        public function hasProperty($property) {
                return isset($this->data[$property]);
        }

        public function getProperty($property) {
                if (!$this->hasProperty($property))
                        return '';
                $format = MetatagsFormat::get($property);
                if ($format !== false) {
                        // Format prior to output if available
                        $format->setInput($this->data[$property]);
                        return $format->getFormat();
                }
                // Otherwise return unaltered input
                return $this->data[$property];
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
}

?>
