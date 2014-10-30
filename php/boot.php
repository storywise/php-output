<?php

// Set default timezone in PHP 5.
if (function_exists('date_default_timezone_set'))
        date_default_timezone_set('Europe/Amsterdam');

define('URL', 'http://localhost:8080/php-output/php-output/');
define('ROOT', '');
define('ABS_ROOT', '');

define('DIR_SEP', '/');
define('FOLDER_CACHE', 'cache/');
define('FOLDER_CONTENT_NOPATH', 'serve/');
define('FOLDER_CONTENT', ROOT . FOLDER_CONTENT_NOPATH);
define('FOLDER_TEMPLATE', FOLDER_CONTENT . 'template/');
define('LOCAL', true);
define('FOLDER_FRAMEWORK', 'php/framework/');

// Get autoloader
require_once ROOT . FOLDER_FRAMEWORK . "autoload/Autoload.php";

// Register
Autoload::register();

if (LOCAL) {

        $GLOBALS['__xitt'] = 0;

        function x() {
                $numargs = func_num_args();
                $arg_list = func_get_args();
                echo "<pre>\n__________________________\n";
                echo "<b>Output {$GLOBALS['__xitt']}:</b><br>\n";
                foreach ($arg_list as $key => $value) {
                        $id = $key + 1;
                        echo print_r($value, true) . "\n";
                }
                echo "</pre>";
                $GLOBALS['__xitt']++;
        }

        function xx() {
                $numargs = func_num_args();
                $arg_list = func_get_args();
                foreach ($arg_list as $key => $value) {
                        $id = $key + 1;
                        echo "<b>Output $id:</b><br>\n";
                        echo "<pre>" . print_r($value, true) . "</pre>\n";
                }
                exit;
        }

}
?>