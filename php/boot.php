<?php

// Instance specific
define('DB_NAME_DEFAULT', 'identitystorywise');
define('URL', 'http://localhost:8080/php-output/php-output/');


// For later use
define('ROOT', '');
define('ABS_ROOT', '');

// Config general 
if (function_exists('date_default_timezone_set'))
        date_default_timezone_set('Europe/Amsterdam');

define('DIR_SEP', '/');
define('FOLDER_CACHE', 'cache/');
define('FOLDER_CONTENT_NOPATH', 'serve/');
define('FOLDER_CONTENT', ROOT . FOLDER_CONTENT_NOPATH);
define('FOLDER_TEMPLATE', FOLDER_CONTENT . 'template/');
define('LOCAL', true);
define('FOLDER_FRAMEWORK', 'php/framework/');
define('BASE64_ID_SEED', 28486.5);

/** Where are media files stored */
define('FOLDER_MEDIA_ARCHIVE', "static/archive/media/");
/** Are we in a shared media folder setup? i.e. Multiple Profiles */
define('SHARED_MEDIA', !is_dir(FOLDER_MEDIA_ARCHIVE));
/** Where does the media folder reside relatively? */
define('FOLDER_MEDIA_REL', SHARED_MEDIA ? ROOT . FOLDER_MEDIA_ARCHIVE : FOLDER_MEDIA_ARCHIVE);
/** Where does the media folder reside absolutely? Regularly */
define('FOLDER_MEDIA', SHARED_MEDIA ? ABS_ROOT . FOLDER_MEDIA_ARCHIVE : URL . FOLDER_MEDIA_ARCHIVE);
/** Where are initial file uploads copied into? */
define('FOLDER_MEDIA_TEMP', FOLDER_MEDIA_REL . 'temp/');

// Get autoloader
require_once ROOT . FOLDER_FRAMEWORK . "autoload/Autoload.php";

// Register
Autoload::register();

// Capture all exception activity 
set_exception_handler(array("ExceptionHandler", "exception"));
set_error_handler(array("ExceptionHandler", "error"), E_ERROR);

class ExceptionHandler {

        public static function error($errno, $errstr, $errfile, $errline) {
                defaultCatcher($errstr);
        }

        public static function exception(Exception $e) {
                if (LOCAL)
                        self::defaultCatcher($e->getMessage() . ' ' . print_r($e->getTrace(), true));
                else
                        self::defaultCatcher($e->getMessage());
        }

        public static function defaultCatcher($error) {
                echo '<pre style="padding:10px;background:#EEE;color:#000;">';
                echo "PHP-Output &mdash; Exception:\n\n";
                echo $error;
                echo "</pre>";
                exit;
        }

}


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