<?php

class OutputAssets extends Instance {

        const PACKAGE = 'package';

        static public $TYPE_CSS = 'css';
        static public $TYPE_JS = 'js';
        static public $SORT_CSS = 'css';
        static public $SORT_JS = 'js';
        static public $SORT_JS_PACKAGE = 'js_package';
        static public $SORT_JS_PRIORITY = 'js_priority';
        static public $SORT_CSS_PRIORITY = 'css_priority';
        private $sharedAssetsAdded = false;
        private $includes;
        private $jsVarsPriority;
        private $jsVars;
        private $cssVars;

        /**
         * 
         * @return OutputAssets
         */
        public static function get() {
                $args = func_get_args();
                array_unshift($args, 'assets');
                $oa = Instance::create('Output', $args, Instance::$SINGLETON, Instance::$NOVIEW, Instance::$NOMODEL);
                return $oa;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
                // Contain all the assets that will be included throughout the page ( js/css )
                $this->includes = array();
        }

        public function getIncludes($merged = false) {
                if (!$merged)
                        return $this->includes;
                $arr = array();
                foreach ($this->includes as $key => $list) {
                        $arr = array_merge($arr, $list);
                }
                if (count($arr) > 0)
                        return $arr;
                return false;
        }

        public function getContentFolder() {
                return FOLDER_CONTENT;
        }

        /**
         * Are there any files pending for the type required?
         * @param type $type
         * @return type
         */
        public function hasType($type) {
                if (!isset($this->includes[$type]))
                        return false;
                return ToolsArray::is($this->includes[$type]);
        }

        public function getType($type) {
                if (!isset($this->includes[$type]))
                        return false;
                return $this->includes[$type];
        }

        public function getTypeSorted($type) {
                // asort returns boolean, references array
                asort($this->includes[$type]);
                // return sorted array
                return $this->includes[$type];
        }

        public function setType($type, $value) {
                $this->includes[$type] = $value;
                return $this;
        }

        /**
         * Retrieve all javascript variables combined, both normal and priority.
         * Priority vars will merge into the result last, this means that any
         * variables present in both lists, will end up having the priority value.
         * @return array
         */
        public function getJsVars() {
                $arr = array();
                if (ToolsArray::is($this->jsVars))
                        $arr = array_merge($arr, $this->jsVars);

                if (ToolsArray::is($this->jsVarsPriority))
                        $arr = array_merge($arr, $this->jsVarsPriority);

                return $arr;
        }

        /**
         * Retrieve CSS snippets that need to be placed inline of the document.
         * This could be handy to utilize when you have paths that need prepending
         * by a ROOT or FOLDER variable / constant.
         */
        public function getCSSVars() {
                return $this->cssVars;
        }

        /**
         * 
         * @param type $path
         * @param type $type
         * @param type $skipURL
         */
        private function addToType($path, $type, $skipURL = false) {

                // Array exist? Create one?
                if (!isset($this->includes[$type]))
                        $this->includes[$type] = array();

                // Check duplicate to prevent double loading
                if (in_array($path, $this->includes[$type]))
                        return false;

                if (!$skipURL) {
                        // Bit nasty, strip the relative and replace with absolute
                        $fixedPath = str_replace($this->getContentFolder(), '', $path);
                        $path = ABS_ROOT . FOLDER_CONTENT_NOPATH . $fixedPath;
                }

                // Add path to specific include type
                array_push($this->includes[$type], $skipURL ? $path : $path);

                return true;
        }

        /**
         * Will force to skip the URL prefix
         * 
         * @param type $folder
         * @param type $fileExtension
         * @param type $sortingType
         * @param type $skipURLPrefix
         */
        public function registerCacheFiles($folder, $fileExtension, $sortingType, $skipURLPrefix = false) {
                $this->registerFiles($folder, $fileExtension, $sortingType, true);
        }

        /**
         * 
         * @param String $folder                       Folder to search in
         * @param String $type                          Type of data we are searching for
         * @param String $packageName       Optional package name
         * @param Boolean $skipURLPrefix   Want to prefix the site's URL by default?     
         * @return void
         */
        public function registerFiles($folder, $fileExtension, $sortingType, $skipURLPrefix = false) {

                if (strpos($folder, FOLDER_CONTENT) === false) {
                        $folder = $this->getContentFolder() . $folder;
                }

                if (is_dir($folder)) {

                        $fs = new Filesystem();
                        $result = $fs->getTree($folder);
                        if (!is_array($result))
                                return;
                        foreach ($result as $key => $value) {
                                if ($fs->getExtension($value) == $fileExtension)
                                        $this->addToType($value, $sortingType, $skipURLPrefix);
                        }
                }
        }

        public function addSharedAppPackage(AppLocator $app, $specificPackage = false) {
                $from = $app->getPath();
                // Make sure from ends with forward slash
                $from = rtrim($from, '/') . '/';

                if ($specificPackage !== false) {
                        $pathSpecific = $from . self::PACKAGE . '/' . $specificPackage . '/';
                        $pathJs = $pathSpecific;
                        $pathCss = $pathSpecific;
                } else {
                        // Default css and js folders
                        $pathJs = $from . OutputAssets::$SORT_JS;
                        $pathCss = $from . OutputAssets::$SORT_CSS;
                }

                // Specify to asset folders in both cases
                $pathJs = $pathJs;
                $pathCss = $pathCss;

                $this->registerCacheFiles($pathJs, OutputAssets::$SORT_JS, OutputAssets::$SORT_JS_PACKAGE);
                $this->registerCacheFiles($pathCss, OutputAssets::$TYPE_CSS, OutputAssets::$TYPE_CSS);
        }

        public function addTemplatePackage($template, $packageName, $priority = false) {

                $from = $this->getTemplatePackageFolder($template, $packageName);

                // Css register cache files by default
                $this->registerCacheFiles($from, OutputAssets::$TYPE_CSS, OutputAssets::$TYPE_CSS);

                // When priority the files are treated as seperate includes and are not agregated into the cache file
                if (!$priority)
                        $this->registerCacheFiles($from, OutputAssets::$SORT_JS, OutputAssets::$SORT_JS_PACKAGE);
                else
                        $this->registerFiles($from, OutputAssets::$SORT_JS, OutputAssets::$SORT_JS_PRIORITY);
        }

        public function addSharedPackage($packageName, $priority = false) {

                $from = $this->getSharedPackageFolder($packageName);

                // Css register cache files by default
                $this->registerCacheFiles($from, OutputAssets::$TYPE_CSS, OutputAssets::$TYPE_CSS);

                // When priority the files are treated as seperate includes and are not agregated into the cache file
                if (!$priority)
                        $this->registerCacheFiles($from, OutputAssets::$SORT_JS, OutputAssets::$SORT_JS_PACKAGE);
                else
                        $this->registerFiles($from, OutputAssets::$SORT_JS, OutputAssets::$SORT_JS_PRIORITY);
        }

        private function getTemplatePackageFolder($template, $packageName) {
                return FOLDER_TEMPLATE . $template . DIR_SEP . self::PACKAGE . DIR_SEP . $packageName;
        }

        private function getSharedPackageFolder($packageName) {
                return 'shared' . DIR_SEP . self::PACKAGE . DIR_SEP . $packageName;
        }

        public function addJavascriptVars($array, $priority = false) {
                if (empty($array))
                        return;
                if (!$priority) {
                        if (is_array($this->jsVars)) {
                                $this->jsVars = array_merge($this->jsVars, $array);
                        } else {
                                $this->jsVars = $array;
                        }
                } else {
                        if (is_array($this->jsVarsPriority)) {
                                $this->jsVarsPriority = array_merge($this->jsVarsPriority, $array);
                        } else
                                $this->jsVarsPriority = $array;
                }
        }

        public function addCSSVars($array) {
                if (empty($array))
                        return;
                if (is_array($this->cssVars)) {
                        $this->cssVars = array_merge($this->cssVars, $array);
                } else {
                        $this->cssVars = $array;
                }
        }

        /**
         * Add a path to the javascript listing
         * @param type $path
         * @param type $skipURL
         */
        public function addJavascript($path, $skipURL = false, $priority = false) {
                if (!$priority)
                        return $this->addToType($path, OutputAssets::$TYPE_JS, $skipURL);
                else
                        return $this->addToType($path, OutputAssets::$SORT_JS_PRIORITY, $skipURL);
        }

        /**
         * Add a path to the css listing
         * @param type $path
         * @param type $skipURL
         */
        public function addCss($path, $skipURL = false, $priority = false) {
                if (!$priority)
                        return $this->addToType($path, OutputAssets::$TYPE_CSS, $skipURL);
                else
                        return $this->addToType($path, OutputAssets::$SORT_CSS_PRIORITY, $skipURL);
        }

        public function addSharedAssets() {
                if ($this->sharedAssetsAdded) {
                        throw new Exception('Shared assets are added automatically. $this->sharedAssets = false; Will prevent this.');
                        return;
                }
                $this->addSharedCSS();
                $this->addSharedJS();
                $this->sharedAssetsAdded = true;
        }

        protected function addSharedCSS() {
                // Get the default style and scripting's from the shared folder
                $from = 'shared' . DIR_SEP . OutputAssets::$TYPE_CSS;
                $this->registerCacheFiles(
                        $from, OutputAssets::$TYPE_CSS, OutputAssets::$SORT_CSS
                );
        }

        protected function addSharedJS() {
                $from = 'shared' . DIR_SEP . OutputAssets::$TYPE_JS;
                $this->registerCacheFiles(
                        $from, OutputAssets::$TYPE_JS, OutputAssets::$SORT_JS
                );
        }

        public function addAssetsByTemplate($template) {

                $assets = OutputAssets::get();
                $workDir = FOLDER_TEMPLATE . $template . DIR_SEP;

                // Register template's css files to be inserted in a cache file
                $assets->registerCacheFiles(
                        $workDir . OutputAssets::$TYPE_CSS, OutputAssets::$TYPE_CSS, OutputAssets::$SORT_CSS
                );

                // Register template's javascript files to be inserted in a cache file
                $assets->registerCacheFiles(
                        $workDir . OutputAssets::$TYPE_JS, OutputAssets::$TYPE_JS, OutputAssets::$SORT_JS
                );
        }

}

?>
