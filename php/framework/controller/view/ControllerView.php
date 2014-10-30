<?php

/**
 * Description of ControllerView
 * @author merten
 */
class ControllerView extends InstanceView {

        protected $html;
        protected $body;

        public function __construct() {
                parent::__construct();
        }
        
        public function head($meta = null, $useSharedAssets = true) {

                // Shortcut to the assetes
                $assets = OutputAssets::get();

                // Header properties in Javascript and CSS
                $jsVars = $assets->getJsVars();
                $cssHead = $assets->getCSSVars();

                // Add shared assets from serve/shared/js and css
                if ($useSharedAssets)
                        $assets->addSharedAssets();

                $html = &$this->html;
                $html->docType()
                        //->legacy() skip legacy css classes
                        ->setHead($meta)
                        ->setVariables($jsVars)
                        ->setHeadCSS($cssHead)
                ;

                // Proceed with includes and external files
                $js = OutputAssets::$SORT_JS;
                $js_pr = OutputAssets::$SORT_JS_PRIORITY;
                $css = OutputAssets::$SORT_CSS;
                $css_pr = OutputAssets::$SORT_CSS_PRIORITY;
                $js_p = OutputAssets::$SORT_JS_PACKAGE;

                //==================================================================
                // Place priority Javascripts
                $html->listJavascript($assets->getType($js_pr), $js_pr);

                //==================================================================
                // Handle cache for shared package Javascript includes
                if ($assets->hasType($js_p)) {
                        $cJsPackage = new CacheListing($assets->getType($js_p), OutputAssets::$TYPE_JS);
                        $assets->setType($js_p, array(URL . $cJsPackage->getPath()));
                        // Place just refreshed or retrieved cache for these files
                        $html->listJavascript($assets->getType($js_p), $js_p);
                }

                //==================================================================
                // Handle cache for default Javascript includes
                if ($assets->hasType($js)) {
                        $cachedJavascriptsPath = new CacheListing($assets->getType($js), OutputAssets::$TYPE_JS);
                        $assets->setType($js, array(URL . $cachedJavascriptsPath->getPath()));

                        // Place just refreshed or retrieved cache for these files
                        $html->listJavascript($assets->getType($js), $js);
                }

                //==================================================================
                // Handle cache for priority CSS includes
                if ($assets->hasType($css_pr)) {
                        // Place just refreshed CSS cache list
                        $html->listCSS($assets->getType($css_pr), $css_pr);
                }

                //==================================================================
                // Handle cache for default CSS includes
                if ($assets->hasType($css)) {
                        $cssFiles = $assets->getTypeSorted($css);
                        $cachedCSSPath = new CacheListing($cssFiles, OutputAssets::$TYPE_CSS);
                        $assets->setType($css, array(URL . $cachedCSSPath->getPath()));
                        // Place just refreshed CSS cache list
                        $html->listCSS($assets->getType($css), $css);
                }
        }
        
        public function getHtml() {
                if (!isset($this->html))
                        $this->html = new Html();

                return $this->html;

                //$assets = OutputAssets::get();
                //$assets->addSharedPriorityPackage('00_jquery');
        }

        public function getBody() {
                if (!isset($this->body)) {
                        $this->body = new HtmlBody();
                        $this->getHtml()->addChild($this->body);
                }
                return $this->body;
        }
        
        public function output() {
                $this->getHtml()->output();
        }

}

?>