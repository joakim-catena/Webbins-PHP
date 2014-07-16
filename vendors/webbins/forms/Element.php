<?php namespace Webbins\Forms;

class Element {
    private $type;
    private $action;
    private $method;
    private $name;
    private $id;
    private $attr;
    private $className;
    private $value;
    private $placeholder;
    private $style;
    private $custom;
    private $label;
    private $labelBefore = false;
    private $labelAfter = false;

    public function __construct() {

    }

    public function setAction($action) {
        $this->action = $action;
    }

    public function getAction() {
        return $this->action;
    }

    public function setMethod($method) {
        $this->method = strtoupper($method);
    }

    public function getMethod() {
        return $this->method;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function getID() {
        return $this->id;
    }

    public function setAttr($attr) {
        $this->attr = $attr;
    }

    public function getAttr() {
        return $this->attr;
    }

    public function setClassName($className) {
        $this->className = $className;
    }

    public function getClassName() {
        return $this->className;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

    public function getStyle() {
        return $this->style;
    }

    public function setCustom($custom) {
        $this->custom = $custom;
    }

    public function getCustom() {
        return $this->custom;
    }

    public function setLabelBefore($label) {
        $this->setLabel($label);
        $this->labelBefore = true;
    }

    public function getLabelBefore() {
        return $this->labelBefore;
    }

    public function setLabelAfter($label) {
        $this->setLabel($label);
        $this->labelAfter = true;
    }

    public function getLabelAfter() {
        return $this->labelAfter;
    }

    private function setLabel($label) {
        $this->label = $label;
    }

    public function getLabel() {
        return $this->label;
    }
}
