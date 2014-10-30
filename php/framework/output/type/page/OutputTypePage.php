<?php

/**
 * In case you like to extend ouput for a custom project task,
 * do so here. Keeping the Output class in tact.
 */
class OutputTypePage extends Output {

        private $template;
        private $postHeadApps = array();
        private $app = array();
        private $html;

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {

                // Fetch template from request, for example: "page", but not "xhr"
                $templateRequest = OutputParam::get(0);

                // Set default template if no request was made ( homepage )
                $template = $templateRequest === false ? 'page' : $templateRequest;

                // Get the template controller, if exists
                $this->controller = Controller::get($template);

                if ($this->controller === false) {
                        // Requested template is missing
                        $this->controller = Controller::get(Output::NOTFOUND);
                }

                if ($this->controller !== false) {

                        // Prepare the page for output
                        $this->controller->prepare();
                } else
                        throw new Exception('Request not found, but notfound controller is missing.');
        }

        public function getAppController($appId) {
                if (isset($this->app[$appId]))
                        return $this->app[$appId];
                return false;
        }

        /* public function getHtml() {
          return $this->html;
          }

          public function getBody() {
          return $this->body;
          }

          public function getController() {
          return $this->controller;
          }

          public function getModel() {
          return $this->getController()->getModel();
          }

          public function getView() {
          return $this->getController()->getView();
          } 

        public function setParam($id) {
                OutputParam::set($id);
        }

        public function getParam($id) {
                return OutputParam::get($id);
        }

        /**
         * @param String $pageid        Get the path towards the requested content
         * @return type
         */
        protected function getContentPath($folder, $template) {
                return FOLDER_TEMPLATE . $folder . DIR_SEP . $template . '.php';
        }

        protected function getTemplateDir($folder) {
                return FOLDER_TEMPLATE . $folder . DIR_SEP;
        }

        /**
         * Prepare model, view and controller custom to the template
         * @param type $folder
         * @throws Exception
         
        public function setMVC($folder) {
                if (isset($this->model) || isset($this->view)) {
                        throw new Exception("Output is already prepared.");
                }

                $this->controller = Controller::get($folder);
        }*/

        /**
         * Set the MVC that belongs to the app
         * @param type $appPath
         * @param type $appName
         * ->getPath(), $app->getKey());
         */
        public function setAppMVC(AppLocator $app) {

                if (!$app->isValid()) {
                        return false;
                }

                // App Mvc path exists?
                if (is_dir($app->getMVCPath())) {

                        $appController = Instance::create('Controller', array($app->getKey()), Instance::$AUTH, Instance::$NOCHAIN);

                        if ($appController === false)
                                throw new Exception("At least a Controller is required for app: {$app->getKey()} ");

                        // Store the app for access in head and body mechanics
                        $this->app[$app->getKey()] = $appController;

                        return true;
                }
        }

        /**
         * 
         * @param String $folder                Folder in which the page will exist
         * @param type $template                  Template / Page
         */
        public function load($folder, $template) {

                $this->template = $template;

                // Retrieve the content path of the template which is serve / template / template .php
                $path = $this->getContentPath($folder, $this->template);

                // Does the template exist?
                $exists = file_exists($path);

                if ($exists) {

                        $assets = OutputAssets::get();
                        $workDir = FOLDER_TEMPLATE . $folder . DIR_SEP;

                        // Register template's css files to be inserted in a cache file
                        $assets->registerCacheFiles(
                                $workDir . OutputAssets::$TYPE_CSS, OutputAssets::$TYPE_CSS, OutputAssets::$SORT_CSS
                        );

                        // Register template's javascript files to be inserted in a cache file
                        $assets->registerCacheFiles(
                                $workDir . OutputAssets::$TYPE_JS, OutputAssets::$TYPE_JS, OutputAssets::$SORT_JS
                        );

                        // Prepare the model and view that might have been customized
                        $this->setMVC($folder);
                } else {
                        $defaultWhenUnavailable = 'page';
                        // Default to page to see if the requested template exists there
                        $this->setMVC($defaultWhenUnavailable);

                        // $this->setParam($this->template);
                        // Same folder as template in this case
                        $path = $this->getContentPath($defaultWhenUnavailable, $defaultWhenUnavailable);
                }

                // Now finally bring the template into action
                require $path;
        }

        /**
         * 
         * @param Array $apps
         * @param Metatags $mt
         * @throws Exception
         */
        public function addSharedApps($apps, $pageId, $mt = false) {

                if (count($apps) > 0) {
                        for ($i = 0; $i < count($apps); $i++) {

                                // name $app is required for inside the template
                                $app = new AppLocator($apps[$i]);

                                if ($app->isValid()) {
                                        $this->setAppMVC($app);
                                }
                                if ($app->hasHead()) {
                                        if (isset($this->app[$app->getKey()])) {
                                                $controller = $this->app[$app->getKey()];
                                        }

                                        // Allow app to be aware
                                        ControllerApp::setApp($app);

                                        require $app->getHead();
                                }

                                // Add css and js from plugin package
                                $assets = OutputAssets::get();
                                $assets->addSharedAppPackage($app);

                                if ($app->hasTemplate()) {

                                        // Associate the apps by pageId
                                        if (!isset($this->postHeadApps[$pageId]))
                                                $this->postHeadApps[$pageId] = array();

                                        // Register template for later
                                        array_push($this->postHeadApps[$pageId], $app);
                                }
                        }
                }
        }

        /**
         * Adds the related apps indicated as above or below
         * @param HtmlDiv $divStart
         * @param int  $pageId
         */
        public function addRemainingApps(HtmlAbstract $divInner, $pageId) {

                if (!isset($this->postHeadApps[$pageId]))
                        return;

                // Use __ prefix because the variable is available in plugin space
                $__arr = $this->postHeadApps[$pageId];

                // How many plugins for this pageId?
                $max = count($__arr);

                $__findPageDiv = $divInner->getByClass("custom_$pageId");

                if ($max > 0) {
                        for ($i = 0; $i < $max; $i++) {

                                // Name $app is required for inside the template
                                $app = $__arr[$i];

                                // Some plugins dont have any mvc structure
                                if (isset($this->app[$app->getKey()]))
                                        $controller = $this->app[$app->getKey()];

                                // Force update of this var every cycle,
                                // so plugins dont override value for each other
                                // IF NO BODY AVAILABLE/EMPTY, THIS IS EMPTY TOO!
                                $div = $__findPageDiv === false ? $this->getBody() : $__findPageDiv[0];

                                // Allow app to be aware
                                ControllerApp::setApp($app);

                                // Top and bottom adding of plugins
                                require $app->getTemplate();
                        }

                        // Plugins added
                        return true;
                }

                // No plugins added
                return false;
        }


        public function output() {

                parent::output();

                if (LOCAL) {

                        $bench = Bench::output(true);

                        echo "\n\n<!-- Local performance report -->\n\n";
                        echo $bench;
                        /* $count = count(DbQuery::$log);
                          $str = print_r(array_count_values(DbQuery::$log), true);
                          echo '<!-- ' . $count . 'x $q, ' . $str . ' -->'; */
                }
        }

}

?>