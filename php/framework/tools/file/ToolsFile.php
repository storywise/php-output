<?php

/**
 * Description of ToolsFile
 * @author merten
 */
class ToolsFile {

        public static function getExtensionByPath( $path ) {
                $path_info = pathinfo($path);
                return !empty($path_info['extension']) ? $path_info['extension'] : false;
                //return str_replace('.', '', strstr($path, '.', false));
        }

}

?>