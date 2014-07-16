<?php namespace Webbins\Forms;

use Exception;
use DOMDocument;
use DOMText;

require('Element.php');

class Form {
    /**
     * Stores the instance of the class.
     * @var  View
     */
    private static $self;

    private static $forms = array();

    private static $inputs = array();

    private static $labels = array();

    private static $currentElement = NULL;

    /**
     * Construct.
     * @param  string  $appPath
     * @param  string  $viewsPath
     */
    public function __construct() {
        self::$self = $this;
    }

    public static function open() {
        self::$currentElement = new Element();
        self::$forms[] = self::$currentElement;
        return self::$self;
    }

    public static function close() {
        $tmp = '';

        $form = self::$forms[Count(self::$forms)-1];

        $dom = new DOMDocument('1.0', 'utf-8');
        $formElement = $dom->createElement('form');

        if ($form->getAction())    $formElement->setAttribute('action', $form->getAction());
        if ($form->getMethod()) {
            if ($form->getMethod() == 'GET' || $form->getMethod() == 'POST') {
                $formElement->setAttribute('method', $form->getMethod());
            } else {
                $formElement->setAttribute('method', 'POST');
                $methodInput = $dom->createElement('input');
                $methodInput->setAttribute('type', 'hidden');
                $methodInput->setAttribute('name', '_method');
                $methodInput->setAttribute('value', $form->getMethod());
                $formElement->appendChild($methodInput);
            }
        }

        foreach (self::$inputs as $input) {
            if ($input->getLabel()) {
                $labelElement = $dom->createElement('label');
                $labelText = new DOMText($input->getLabel());
            }

            $inputElement = $dom->createElement('input');

            if ($input->getType())           $inputElement->setAttribute('type',           $input->getType());
            if ($input->getName())           $inputElement->setAttribute('name',           $input->getName());
            if ($input->getID())             $inputElement->setAttribute('id',             $input->getID());
            if ($input->getAttr())           $inputElement->setAttribute('attr',           $input->getAttr());
            if ($input->getClassName())      $inputElement->setAttribute('class',          $input->getClassName());
            if ($input->getValue())          $inputElement->setAttribute('value',          $input->getValue());
            if ($input->getPlaceholder())    $inputElement->setAttribute('placeholder',    $input->getPlaceholder());
            if ($input->getStyle())          $inputElement->setAttribute('style',          $input->getStyle());
            if ($input->getCustom())         $inputElement->setAttribute('custom',         $input->getCustom());

            if ($input->getLabel()) {
                $labelElement->appendChild($inputElement);

                if ($input->getLabelBefore()) {
                    $labelElement->insertBefore($labelText, $inputElement);
                } else {
                    $labelElement->appendChild($labelText);
                }
                $formElement->appendChild($labelElement);
            } else {
                $formElement->appendChild($inputElement);
            }

            $dom->appendChild($formElement);
        }

        echo $dom->saveHTML();

        self::clear();
    }

    public static function input() {
        self::$currentElement = new Element();
        self::$inputs[] = self::$currentElement;
        return self::$self;
    }

    public static function submit() {
        self::$currentElement = new Element();
        self::$inputs[] = self::$currentElement;
        return self::$self;
    }

    public static function label($label) {
        self::labelBefore($label);
        return self::$self;
    }

    public static function labelBefore($label) {
        self::$currentElement->setLabelBefore($label);
        return self::$self;
    }

    public static function labelAfter($label) {
        self::$currentElement->setLabelAfter($label);
        return self::$self;
    }

    public function action($action) {
        self::$currentElement->setAction($action);
        return self::$self;
    }

    public function method($method) {
        self::$currentElement->setMethod($method);
        return self::$self;
    }

    public function type($type) {
        self::$currentElement->setType($type);
        return self::$self;
    }

    public function name($name) {
        self::$currentElement->setName($name);
        return self::$self;
    }

    public function id($id) {
        self::$currentElement->setID($id);
        return self::$self;
    }

    public function attr($attr) {
        self::$currentElement->setAttr($attr);
        return self::$self;
    }

    public function className($className) {
        self::$currentElement->setClassName($className);
        return self::$self;
    }

    public function value($value) {
        self::$currentElement->setValue($value);
        return self::$self;
    }

    public function placeholder($placeholder) {
        self::$currentElement->setPlaceholder($placeholder);
        return self::$self;
    }

    public function style($style) {
        self::$currentElement->setStyle($style);
        return self::$self;
    }

    public function custom($custom) {
        self::$currentElement->setCustom($custom);
        return self::$self;
    }

    private static function clear() {
        self::$inputs = array();
        self::$currentElement = NULL;
    }
}
