<?php

class ToolsArray {

        /**
         * Isset must occur outside, otherwise there is still a notice
         * @param type $arr
         * @return type
         */
        public static function is($arr, $index = false) {
                if ($index !== false)
                        @$arr = $arr[$index];
                return isset($arr) && !empty($arr) && is_array($arr) && count($arr) > 0;
        }

        public static function associateResultSetTo($arr, $requestKey, $sortedValueKey) {
                $a = array();
                if (is_array($arr)) {
                        $max = count($arr);
                        for ($i = 0; $i < $max; $i++) {
                                $entry = $arr[$i];

                                $actualKey = $entry[$requestKey];
                                $a[$actualKey] = $entry[$sortedValueKey];
                        }

                        return $a;
                }
                // Unchanged
                return $arr;
        }

}

?>
