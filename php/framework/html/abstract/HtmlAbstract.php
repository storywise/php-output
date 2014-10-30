<?php

class HtmlAbstract {

        public $id;
        public $style;
        public $content;
        protected $class;
        protected $parent;
        protected $children;
        protected $childrenIds;
        protected $childrenClasses;
        protected $properties;
        protected $data;
        protected $type;
        protected $contentAfter = false;
        protected $doClosingTag = true;
        protected $comments;
        private $space = ' ';

        function __construct($type) {
                $this->type = $type;
        }

        protected function addComment($comment) {
                if (!isset($this->comments))
                        $this->comments = array();
                array_push($this->comments, $comment);
        }

        /**
         * Generate property string to add to html element
         * @param array $data
         * @param string $prefix
         * @param boolean $sQuotes
         * @return string
         */
        protected function getProperties($data, $prefix = false, $sQuotes = false) {
                $str = '';
                $prefix = !$prefix ? '' : $prefix;
                $strQuote = $sQuotes ? "'" : '"';
                if (ToolsArray::is($data)) {
                        $str .= ' ';
                        $max = count($data);
                        for ($i = 0; $i < $max; $i++) {
                                $arr = $data[$i];
                                // Single quotes to allow for JSON brodder!
                                $str .= $prefix . "{$arr['key']}={$strQuote}{$arr['value']}{$strQuote}";
                                if ($i < $max - 1)
                                        $str .= ' ';
                        }
                }
                return $str;
        }

        public function addContentAfter($content, $title = false) {
                return $this->setContentAfter($content, $title);
        }

        public function setContentAfter($content, $title = false) {
                $r = $this->setContent($content, $title);
                $this->contentAfter = true;
                return $r;
        }

        public function addContent($content, $title = false) {
                return $this->setContent($content, $title);
        }

        public function setContent($content, $title = false) {
                $this->contentAfter = false;
                $this->content = $content;
                if ($title)
                        $this->setTitle($content);
                return $this;
        }

        public function setTitle($title) {
                $this->addProperty('title', $title);
        }

        public function setData($key, $value) {
                return $this->addData($key, $value);
        }

        /**
         * Add data fields to the html element ( data-test="" )
         * @param type $key
         * @param type $value
         */
        public function addData($key, $value) {
                if (!isset($this->data))
                        $this->data = array();
                // Clear any existing before adding new
                // $this->removeData( $key );
                array_push($this->data, array('key' => $key, 'value' => strip_tags($value)));
                return $this;
        }

        public function getData($key) {
                if (!isset($this->data))
                        return false;
                for ($i = 0; $i < count($this->data); $i++) {
                        $d = $this->data[$i];
                        if (isset($d[$key])) {
                                return $d['value'];
                        }
                }
        }

        public function removeData($key, $value = false) {
                if (!isset($this->data))
                        return $this;
                for ($i = 0; $i < count($this->data); $i++) {
                        $d = $this->data[$i];
                        if ($d['key'] == $key && ( $value === false || $d['value'] == $value)) {
                                array_splice($this->data, $i, 1);
                                return $this;
                        }
                }
                return $this;
        }

        public function setProperty($key, $value) {
                return $this->addProperty($key, $value);
        }

        /**
         * Add regular properties to html element ( href="" )
         * @param type $key
         * @param type $value
         */
        public function addProperty($key, $value) {
                if (!isset($this->properties))
                        $this->properties = array();
                array_push($this->properties, array('key' => $key, 'value' => $value));
                return $this;
        }

        public function addId($id) {
                return $this->setId($id);
        }

        public function setId($id) {
                $this->id = $id;
                return $this;
        }

        /**
         * Get id of element in either prepared html or plain output
         * 
         * @param type $html
         * @return type
         */
        public function getId($html = true) {
                if (!$html)
                        return isset($this->id) ? $this->id : false;
                return isset($this->id) ? "id=\"{$this->id}\"{$this->space}" : "";
        }

        protected function getClass() {
                if (!isset($this->class))
                        return '';
                $str = implode(' ', $this->class);
                return "class=\"{$str}\"{$this->space}";
        }

        public function addClass($class) {
                if (!isset($this->class))
                        $this->class = array();
                if (!in_array($class, $this->class))
                        array_push($this->class, $class);

                return $this;
        }

        protected function getStyle() {
                return isset($this->style) ? "style=\"{$this->style}\"{$this->space}" : "";
        }

        public function addStyle($prop, $style) {
                $prop = rtrim(trim($prop), ':');
                $prop = $prop . ':';
                $style = rtrim(trim($style), ';');
                $style = "$style;";
                if (isset($this->style))
                        $this->style .= $prop . $style;
                else
                        $this->style = $prop . $style;
                return $this;
        }

        private function increaseIndexAt($arr, $index) {
                if (!is_array($arr))
                        return;

                foreach ($arr as $key => $value) {
                        if (!is_array($value) && $value >= $index)
                                $arr[$key]++;
                        else {
                                $arrMax = count($value);
                                for ($i = 0; $i < $arrMax; $i++) {
                                        $arrIndex = $value[$i];
                                        if ($arrIndex >= $index)
                                                $arr[$key][$i]++;
                                }
                        }
                }
                return $arr;
        }

        public function addChildAt(HtmlAbstract $child, $index) {
                // We are forcing a child into a new position
                // We must increase indexes by one when > $forceAtIndex
                $this->childrenIds = $this->increaseIndexAt($this->childrenIds, $index);
                $this->childrenClasses = $this->increaseIndexAt($this->childrenClasses, $index);
                $this->prepareAddChild($child, $index);
                array_splice($this->children, $index, 0, array($child));
        }

        public function addChild(HtmlAbstract $child) {
                $this->prepareAddChild($child);
                // Finally give the child a home to live in.
                array_push($this->children, $child);
                return $this;
        }

        public function getParent() {
                return $this->parent;
        }

        public function setParent(HtmlAbstract $parent) {
                $this->parent = $parent;
        }

        private function prepareAddChild(HtmlAbstract $child, $forceAtIndex = false) {

                // Make the child aware of its parent
                $child->setParent($this);

                // What id can we expect the incoming child to receive?
                $upcomingId = $forceAtIndex !== false ? $forceAtIndex : count($this->children);

                // Children present?
                if (!isset($this->children))
                        $this->children = array();

                // Does the incoming child have an id?
                // If so, associate to allow for getById
                if (isset($child->id)) {
                        if (!isset($this->childrenIds))
                                $this->childrenIds = array();
                        $this->childrenIds[$child->id] = $upcomingId;
                }
                // Does the incoming child have any classes?
                // If so, associate to allow for getByClass
                if (isset($child->class)) {
                        if (!isset($this->childrenClasses))
                                $this->childrenClasses = array();
                        $classes = $child->class; // Was explode by ' ' space
                        $max = count($classes);
                        for ($i = 0; $i < $max; $i++) {
                                $className = $classes[$i];
                                // There can be multiple elements for one class
                                if (!isset($this->childrenClasses[$className]))
                                        $this->childrenClasses[$className] = array();
                                array_push($this->childrenClasses[$className], $upcomingId);
                        }
                }
        }

        protected function setClosingTag($closingTag = true) {
                $this->doClosingTag = $closingTag;
        }

        protected function getChildren() {
                if (!isset($this->children))
                        return '';
                $str = '';
                $max = count($this->children);
                for ($i = 0; $i < $max; $i++) {
                        $h = $this->children[$i];
                        $str .= $h->getOutput();
                }
                return $str;
        }

        public function getById($id) {
                // Fetch child by id, in id table
                $idResult = $this->getFromSource($this->childrenIds, 'id', $id);
                if ($idResult !== false)
                        return $idResult;
                // Fetch child by id, in class table
                $classResult = $this->getFromSource($this->childrenClasses, 'id', $id);
                return $classResult;
        }

        public function getByClass($class) {
                // Find child by classname in class table
                $classResult = $this->getFromSource($this->childrenClasses, 'class', $class);
                if ($classResult !== false)
                        return $classResult;
                // Find child by classname in id table
                $idResult = $this->getFromSource($this->childrenIds, 'class', $class);
                return $idResult;
        }

        public function getFromSource($srcArr, $field, $key) {
                // Is the current html object having the required id or class?
// >>  TODO Does the include spaced out classes? 'class1 class2' ? NO!!!
                if ($this->$field == $key)
                        return $this;

                // No children associations in source.
                // Means that there might be no classes, nor id's defined of the children
                if (!$srcArr) {
                        return false;
                }

                // Is one of current children's ids?
                // Return HAbstract object, mapping goes by array index
                @$assoc = $srcArr[$key];

                if (!is_array($assoc) && !empty($assoc)) {
                        // Single association, not an array: found!
                        return $this->children[$srcArr[$key]];
                } else if (is_array($assoc)) {

                        // More then one class association has been found
                        // That means a list of indices relating to children
                        $max = count($assoc);
                        $list = array();

                        for ($i = 0; $i < $max; $i++) {
                                $childIndex = $assoc[$i];
                                array_push($list, $this->children[$childIndex]);
                        }
                        return $list;
                }


                // Is one of children's children?
                $max = count($this->children);
                for ($i = 0; $i < $max; $i++) {
                        $h = $this->children[$i];

                        // Will return HAbstract object if found
                        $func = 'getBy' . ucfirst(strtolower($field));

                        // Variable method either getByClass or getById
                        $habstract = $h->$func($key);

                        if ($habstract !== false)
                                return $habstract;
                }

                // Nothing, nada, zip
                return false;
        }

        public function getOutput() {
                $innerTag = $this->getInnerTag();
                $children = $this->getChildren();
                if (!$this->doClosingTag) {
                        return "\n<{$innerTag}/>\n";
                }
                $cData = "";
                if (isset($this->comments)) {
                        $cData .= "<!--\n\t";
                        $maxCdata = count($this->comments);
                        for ($i = 0; $i < $maxCdata; $i++) {
                                $cData .= $this->comments[$i];
                                if ($i != $maxCdata - 1)
                                        $cData .= "\n\t";
                        }
                        $cData .= "-->";
                }
                return $cData . ($this->contentAfter) ?
                        "\n<{$innerTag}>{$children}{$this->content}</{$this->type}>\n" :
                        "\n<{$innerTag}>{$this->content}{$children}</{$this->type}>\n";
        }

        protected function getInnerTag() {
                $data = $this->getProperties($this->data, 'data-', true);
                $props = $this->getProperties($this->properties);
                return rtrim($this->type . ' ' . $this->getId() . $this->getClass() . $this->getStyle() . $data . $props, ' ');
        }

        public function output() {
                echo $this->getOutput();
        }

}

?>