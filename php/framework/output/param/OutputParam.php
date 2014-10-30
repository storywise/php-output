<?php

class OutputParam {

        private static $param = array();

        public static function parse($str = false) {
                if ($str === false) {
                        $trim = '/\^$';
                        $uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';
                        $str = trim($uri, $trim);
                }
                $str = ltrim($str, '/');
                $str = rtrim($str, '/');
                self::$param = explode('/', $str);
        }

        public static function each($function) {
                array_walk(self::$param, $function);
        }

        /**
         * Duplicate Array until chop id, and return it.
         * @param type $id
         * @return type
         */
        public static function chop($id) {
                $arr = array();
                for ($i = 0; $i < count(self::$param); $i++) {
                        if ($i == $id)
                                break;
                        $arr[$i] = self::$param[$i];
                }
                return $arr;
        }

        public static function set($param) {
                array_push(self::$param, strtolower($param));
        }

        public static function has($id) {
                return isset(self::$param[$id]) && !empty(self::$param[$id]);
        }

        /**
         * Integrate a validation pattern here?
         * @param type $id
         * @return type
         */
        public static function get($id = false) {
                if ($id === false)
                        return self::$param;
                return !self::has($id) ? false : self::$param[$id];
        }

        public static function getURI($until = false) {
                $str = 'page';
                for ($i = 0; $i < count(self::$param); $i++) {
                        $str .= '/' . self::$param[$i];
                        if ($until !== false && $until == $i)
                                break;
                }
                return URL . $str;
        }

}

?>
