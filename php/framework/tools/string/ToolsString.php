<?php

class ToolsString {

        /**
         * Create case sensitive class string from either argument string or array.
         * 
         * @param type $mixed
         * @return type
         */
        public static function cls($mixed, $doArrayReturn = false) {
                if (is_string($mixed))
                        return self::toClassStandard($mixed);
                if (is_array($mixed)) {
                        $str = '';
                        $max = count($mixed);
                        for ($i = 0; $i < $max; $i++) {
                                $type = $mixed[$i];
                                if (!$doArrayReturn)
                                        $str .= self::toClassStandard($type);
                                else
                                        $mixed[$i] = self::toClassStandard($type);
                        }
                        if (!$doArrayReturn)
                                return $str;
                        else
                                return $mixed;
                }
                return false;
        }

        public static function toClassStandard($str) {
                if (is_array($str)) {
//print_r(debug_print_backtrace());
                        xx('received an array in Tools::toClassStandard', $str);
                }
                return ucfirst(strtolower($str));
        }

}

?>