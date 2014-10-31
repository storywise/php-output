<?php

/*
 * DUMP!
 * <meta property="fb:admins" content="638320659"/>
  <meta property="fb:app_id" content="255329661182670"/>
 * <meta name="viewport" content="initial-scale=1,user-scalable=yes,maximum-scale=1,width=827">
 * 
  <!--[if lt IE 7]>
  <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
  <![endif]-->


 */

class Html extends HtmlAbstract {

        private $prepend;
        private $head;

        public function __construct() {
                $this->head = array();
                parent::__construct('html');
        }

        public function setViewportWidth($width) {
                $this->viewportWidth = $width;
        }

        public function getOutput() {

                $tidy = new tidy();

                // http://tidy.sourceforge.net/docs/quickref.html
                $options = array(
                    'hide-comments' => !LOCAL,
                    'tidy-mark' => false,
                    'indent' => true,
                    'indent-spaces' => 4,
                    'new-blocklevel-tags' => 'menu,mytag,article,header,footer,section,nav',
                    'new-inline-tags' => 'video,audio,canvas,ruby,rt,rp',
                    'doctype' => '<!DOCTYPE HTML>',
                    //'sort-attributes'     => 'alpha',
                    'vertical-space' => false,
                    'output-xhtml' => true,
                    'wrap' => 180,
                    'wrap-attributes' => false,
                    'break-before-br' => false,
                    'char-encoding' => 'utf8',
                    'input-encoding' => 'utf8',
                    'output-encoding' => 'utf8'
                );

                $tidy->parseString($this->getBody(), $options, 'utf8');

                $document = $this->getPrependStr() . '<' . $this->getInnerTag() . '>' . "\n" . $this->getHead() . $tidy->body() . '</' . $this->type . '>';

                return $document;

                /*
                  $config = array(
                  'indent' => true,
                  'output-xhtml' => false,
                  'wrap' => 200,
                  'tidy-mark' => false,
                  'indent-spaces' => 1
                  );
                  $document = $this->getPrependStr() . '<' . $this->getInnerTag() . '>' . "\n" . $this->getHead() . $this->getBody() . '</' . $this->type . '>';
                  return $document;

                  $tidy = new tidy();

                  $tidy->parseString($this->getBody(), $config, 'utf8');
                  $tidy->cleanRepair();

                  $document = $this->getPrependStr() . '<' . $this->getInnerTag() . '>' . "\n" . $this->getHead() . $tidy->body() . '</' . $this->type . '>';
                  return $document; */
        }

        /**
         * In stead of getChildren, for Html we only have getBody
         */
        private function getBody() {
                return $this->getChildren();
                throw new Exception('Html can only have one child: body');
        }

        /**
         * Retrieve the head part of the Html tag
         * @return string
         */
        private function getHead() {
                $str = "<head>\n\n";
                $str .= "<!-- Identity Storywise, http://www.identitystorywise.com or https://github.com/storywise -->\n\n";
                if (ToolsArray::is($this->head)) {
                        for ($i = 0; $i < count($this->head); $i++) {
                                $str .= $this->head[$i] . "\n";
                        }
                }
                $str .= '</head>' . "\n";
                return $str;
        }

        private function getPrependStr() {
                if (!ToolsArray::is($this->prepend))
                        return '';
                $str = '';
                for ($i = 0; $i < count($this->prepend); $i++) {
                        $str .= $this->prepend[$i] . "\n";
                }
                return $str;
        }

        private function addToHead($str) {
                array_push($this->head, $str);
                return $this;
        }

        public function listJavascript($arr, $label) {
                if (ToolsArray::is($arr)) {
                        $this
                                ->addToHead("")
                                ->addToHead("<!-- $label -->")
                        ;
                        sort($arr);
                        foreach ($arr as $key => $value) {
                                $this->addToHead('<script src="' . $value . '" charset="utf-8"></script>');
                        }
                }
                return $this;
        }

        public function listCSS($arr, $label) {
                if (!empty($arr)) {
                        $this
                                ->addToHead("")
                                ->addToHead("<!-- $label -->")
                        ;
                        sort($arr);
                        foreach ($arr as $key => $value) {
                                $this->addToHead('<link rel="stylesheet" href="' . $value . '" type="text/css">');
                        }
                }
                return $this;
        }

        public function setHeadCSS($vars) {

                if (ToolsArray::is($vars)) {

                        $this
                                ->addToHead("")
                                ->addToHead("<!-- CSS exchange -->")
                                ->addToHead('<style type="text/css">');
                        ;

                        foreach ($vars as $key => $value) {
                                $this->addToHead($value);
                        }
                        $this->addToHead('</style>');
                }
                return $this;
        }

        public function setVariables($vars) {
                if (ToolsArray::is($vars)) {

                        $this
                                ->addToHead("")
                                ->addToHead("<!-- Javascript exchange -->")
                                ->addToHead('<script type="text/javascript">')
                        ;

                        foreach ($vars as $key => $value) {
                                if (strpos(strtolower($key), 'data') !== false)
                                        $this->addToHead("var $key = $value;");
                                else
                                        $this->addToHead("var $key = \"$value\";");
                        }
                        $this->addToHead('</script>');
                }
                return $this;
        }

        public function setHead(Metatags $meta) {

                if (!$meta instanceof Metatags)
                        $meta = new Metatags(); // Use default if no instance was found

                if ($meta->hasProperty(Metatags::FAVICON))
                        $this->addToHead('<link rel="shortcut icon" href="' . $meta->getProperty(Metatags::FAVICON) . '">');

                $this->addToHead('<title>' . $meta->getProperty(Metatags::TITLE) . '</title>')
                        ->addToHead("\n")
                        ->addToHead('<!--Base URL for all paths -->')
                        ->addToHead('<base href="' . URL . '">')
                        ->addToHead("\n")
                ;

                if ($meta->hasCustomTags()) {
                        $this->addToHead("<!-- Custom Meta tags -->");
                        $tags = $meta->getCustomTags();
                        for ($i = 0; $i < count($tags); $i++) {
                                $this->addToHead($tags[$i]);
                        }
                        $this->addToHead("\n");
                }

                $this
                        ->addToHead("<!-- Meta tags -->")
                        ->addToHead('<meta charset="utf-8">')
                ;
                //->addToHead('<meta http-equiv="X-UA-Compatible" content="IE=edge">')

                if ($meta->hasProperty(Metatags::SECRET))
                        $this->addToHead('<meta name="robots" content="' .
                                $meta->getProperty(Metatags::SECRET) . '">');

                if ($meta->hasProperty(Metatags::DESCRIPTION))
                        $this->addToHead('<meta name="description" content="' .
                                $meta->getProperty(Metatags::DESCRIPTION) . '">');

                if ($meta->hasProperty(Metatags::KEYWORDS))
                        $this->addToHead('<meta name="keywords" content="' .
                                $meta->getProperty(Metatags::KEYWORDS) . '">');

                if ($meta->hasProperty(Metatags::COPYRIGHT))
                        $this->addToHead('<meta name="copyright" content="' .
                                $meta->getProperty(Metatags::COPYRIGHT) . '">');

                if ($meta->hasProperty(Metatags::LANGUAGE))
                        $this->addToHead('<meta name="language" content="' .
                                $meta->getProperty(Metatags::LANGUAGE) . '">');

                $this
                        ->addToHead("")
                        ->addToHead("<!--Open Graph's -->");

                if ($meta->hasProperty(Metatags::SITENAME))
                        $this->addToHead('<meta property="og:site_name" content="' .
                                $meta->getProperty(Metatags::SITENAME) . '"/>');

                if ($meta->hasProperty(Metatags::URL))
                        $this->addToHead('<meta property="og:url" content="' .
                                $meta->getProperty(Metatags::URL) . '">');

                if ($meta->hasProperty(Metatags::TITLE))
                        $this->addToHead('<meta property="og:title" content="' .
                                $meta->getProperty(Metatags::TITLE) . '">');

                if ($meta->hasProperty(Metatags::DESCRIPTION))
                        $this->addToHead('<meta property="og:description" content="' .
                                $meta->getProperty(Metatags::DESCRIPTION) . '">');

                if ($meta->hasProperty(Metatags::IMAGE))
                        $this->addToHead('<meta property="og:image" content="' .
                                $meta->getProperty(Metatags::IMAGE) . '">');

                if ($meta->hasProperty(Metatags::VIEWPORT_WIDTH))
                        $this->addToHead('<meta name="viewport" content="width=' .
                                $meta->getProperty(Metatags::VIEWPORT_WIDTH) . ', initial-scale=1, maximum-scale=1">');

                if ($meta->hasProperty(Metatags::TYPE))
                        $this->addToHead('<meta property="og:type" content="' .
                                $meta->getProperty(Metatags::TYPE) . '">');

                // Optional Google Analytics support
                /* $ga = GOOGLE_ANALYTICS;

                  // Check if valid key and add the javascript to open up the gates
                  if (isset($ga) && is_string($ga) && strlen($ga) > 5) {
                  $this->addToHead("<script>\n" .
                  "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n" .
                  "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n" .
                  "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n" .
                  "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n\n" .
                  "ga('create', '" . $ga . "', '" . str_replace('http://www', '', URL) . "');\n" .
                  "ga('send', 'pageview');\n\n" .
                  "</script>");
                  } */

                return $this;
        }

        public function docType() {
                return $this->addPrepend("<!DOCTYPE html>");
        }

        public function legacy() {
                return $this
                                ->addPrepend('<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->')
                                ->addPrepend('<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->')
                                ->addPrepend('<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->')
                                ->addPrepend('<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->')
                ;
        }

        private function addPrepend($str) {
                if (!ToolsArray::is($this->prepend))
                        $this->prepend = array();
                array_push($this->prepend, $str);
                return $this;
        }

}

?>