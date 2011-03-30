<?php

/**
 * My Promotions eCommerce Platform
 * Copyright © 2010-2011 Promocon Group Pty. Ltd.
 */
namespace Cob\Form\Decorator;

/**
 * 
 * 
 * @author Cobby
 */

class SimpleCompositeElement  extends DecoratorAbstract
{

        /**
     * Default placement: surround content
     * @var string
     */
    protected $_placement = null;

    /**
     * Render
     *
     * Renders as the following:
     * <li>$content</li>
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $label = $element->getLabel();

        $label = $this->renderLabel();

        $cssClasses[] = "formElement";

        if($this->isButton()){
            $cssClasses[] = "formButton";
        }

        return $label . $content;
    }

    /**
     * Render the element label
     *
     * @return string
     */
    public function renderLabel()
    {
        if($this->isButton()){
            return '';
        }

        $element = $this->getElement();
        $label = $element->getLabel() . ':';

        if($element->isRequired() && $label != ''){
            $label .= '<span class="red">*</span>';
        }

        return $element->getView()->formLabel($element->getName(), $label, array('escape' => false));
    }

    /**
     * Returns true is the current element is a button
     *
     * @return bool
     */
    public function isButton()
    {
        $helpers = array('button', 'submit', 'reset');
        $helperName = $this->getElement()->helper;

        foreach($helpers as $helper){
            $return = stripos($helperName, $helper) !== false;
            if($return){
                return true;
            }
        }
        return false;
    }

}