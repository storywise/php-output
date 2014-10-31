<?php

class OutputTypeXhr extends Output {

        private $xhr;

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        public function prepare() {

                // Data pushed to the XHR
                $raw = $_POST;

                if (!isset($raw['type']))
                        throw new Exception('Could not complete XHR request');

                // Get the template controller, if exists
                $this->controller = Xhr::get($raw['type']);

                if ($this->controller !== false) {
                        $this->controller->setVars($raw['vars']);
                } else {
                        // Requested template is missing
                        $this->controller = Controller::get(Output::NOTFOUND);
                }

                if ($this->controller !== false) {
                        // Prepare the page for output, whether notfound or xhr match
                        $this->controller->prepare();
                } else
                        throw new Exception('Request not found, but notfound controller is missing.');
        }

        /* public function setMVC($folder) {

          // Will throw exception if not valid
          parent::setMVC($folder);

          // Create Xhr controller for template
          $this->xhr = Xhr::get($folder);

          // Set controller
          $this->xhr->setController($this->getController());
          }

          public function setAppMVC(AppLocator $app) {

          // Xhr, version is unknown
          $status = parent::setAppMVC($app);

          // Allow app to be aware
          ControllerApp::setApp($app);

          if (!$status) {
          return false;
          }

          // Create app xhr controller
          $this->xhr = Xhr::get($app->getKey());

          // Expect it
          if ($this->xhr === false)
          throw new Exception("Missing Xhr controller for app: {$app->getKey()} ");

          // Set it
          $this->xhr->setController(parent::getAppController($app->getKey()));

          return true;
          }

          public function getXhr() {
          return $this->xhr;
          } */
}

?>
