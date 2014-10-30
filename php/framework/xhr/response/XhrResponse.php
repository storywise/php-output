<?php

/**
 * Description of XhrResponse
 * @author merten
 */
class XhrResponse extends Instance {

        /**
         * 
         * @return XhrResponse
         */
        public static function get() {
                $inst = Instance::create(array('xhr', 'response'), false, Instance::$NOMODEL, Instance::$NOVIEW);
                return $inst;
        }

        public function __construct(DbConnect $model = null, $view = null) {
                parent::__construct($model, $view);
        }

        protected $json;
        protected $error;
        protected $assets = false;
        protected $handler = false;

        public function addJson($json) {
                $this->json = $json;
                return $this;
        }

        public function addHandler($handler) {
                $this->handler = $handler;
                return $this;
        }

        public function addError($json) {
                if (!isset($this->error))
                        $this->error = array();
                array_push($this->error, $json);
                return $this;
        }

        public function addAsset($path) {
                if (!$path)
                        return $this;
                if (is_array($path)) {
                        for ($i = 0; $i < count($path); $i++) {
                                if (!is_array($path[$i]))
                                        $this->addAsset($path[$i]);
                        }
                        return $this;
                }
                $type = pathinfo($path, PATHINFO_EXTENSION);
                if (!isset($this->assets))
                        $this->assets = array();
                if (!isset($this->assets[$type]))
                        $this->assets[$type] = array();
                array_push($this->assets[$type], $path);
                return $this;
        }

        public function output() {
                if (isset($this->error))
                        die($this->getOutput('error', $this->error));
                die($this->getOutput('success', $this->json, $this->assets, $this->handler));
        }

        private function getOutput($type, $result, $assets = false, $handler = false) {
                $arr = array('type' => $type, 'result' => $result);
                if ($assets !== false)
                        $arr ['assets'] = $assets;
                if ($handler !== false)
                        $arr ['handler'] = $handler;
                return json_encode($arr);
        }

}

?>