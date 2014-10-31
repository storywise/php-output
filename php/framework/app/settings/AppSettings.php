<?php

class AppSettings {

        private $settings;
        private $default;

        function __construct($settingsString = false, $settingsPriorityString = false) {
                if ($settingsString !== false) {
                        $this->parse($settingsString);
                        if ($settingsPriorityString !== false) {
                                $this->parse($settingsPriorityString);
                        }
                } else
                        $this->parse('');
        }

        public function extractNumber($from) {
                $posPlus = strpos($from, '+');
                $add = $posPlus !== false;
                $posMin = strpos($from, '-');
                $substract = $posMin !== false;

                if ($add) {
                        return (int) substr($from, $posPlus + 1, strlen($from));
                } else if ($substract) {
                        return (int) substr($from, $posMin + 1, strlen($from));
                } else
                        return false;
        }

        /**
         * If no value is set in the App configuration, then this value will be used.
         * @param string $key
         * @param mixed  $value
         */
        public function setDefault($key, $value) {
                $this->default[$key] = $value;
                return $this;
        }

        /**
         * Parse the CSV settings into the settings array, readible by the app.
         * @param string $settingsString
         * @return void
         */
        private function parse($settingsString) {
                if ($settingsString == '')
                        return;

                // Dont override
                if (!is_array($this->settings))
                        $this->settings = array();

                // If there is just one value pair, parse it
                if (strpos($settingsString, ',') === false) {
                        $this->parsePair($settingsString);
                        return;
                }

                // If there are more value pairs
                $s = explode(',', $settingsString);

                $max = count($s);
                if ($max == 0)
                        return;
                // Parse value pairs
                for ($i = 0; $i < count($s); $i++) {
                        $this->parsePair($s[$i]);
                }
        }

        private function parsePair($pair) {
                // Pair is divided by
                $valueDivider = '=';
                // Only a fair pair when the divider is present
                if (strpos($pair, $valueDivider) !== false) {
                        $arrPair = explode($valueDivider, $pair);
                        $k = trim($arrPair[0]);
                        $v = trim($arrPair[1]);
                        $this->settings[$k] = $this->value($v);
                }
        }

        /**
         * 
         * @param type $key
         * @return boolean
         */
        public function get($key) {
                if (isset($this->settings[$key]))
                        return $this->settings[$key];
                else if (isset($this->default[$key]))
                        return $this->default[$key];
                return false;
        }

        /**
         * Override a config setting
         * @param type $key
         * @param type $value
         */
        public function set($key, $value) {
                $this->settings[$key] = $value;
        }

        /**
         * Get the full list of settings, potentially skipping a few properties in param.
         * Usefull when looping through contents
         * @param array $exceptList
         */
        public function getSettingsExcept($exceptList = false) {
                $result = array();
                foreach ($this->settings as $key => $value) {
                        $v = $this->get($key);
                        if (!in_array($key, $exceptList)) {
                                $cv = $value; //$this->value( $value );
                                $result[$key] = $cv;
                        }
                }
                return $result;
        }

        private function value($v) {

                // Check if its a list of values using just one divider for that "|"
                $cv = strpos($v, '|') !== false ? explode('|', $v) : $v;

                if (!is_array($cv)) {
                        // Do basic typing for ints and booleans
                        $isInt = (int) $v > 0;
                        if ($isInt) {
                                $cv = (int) $v;
                                $isBool = $cv == 0 || $cv == 1;
                                if ($isBool)
                                        $cv = (bool) $cv ? true : false;
                        }
                }
                return $cv;
        }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
