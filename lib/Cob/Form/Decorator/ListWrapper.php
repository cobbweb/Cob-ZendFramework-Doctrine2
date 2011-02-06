<?php

/*
Copyright (C) 2011 by Andrew Cobby <cobby@cobbweb.me>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

namespace Cob\Form\Decorator;

/**
 * Wraps form elements in an unordered list
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class ListWrapper extends DecoratorAbstract
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

        return '<li id="' . $element->getName() . '-element" class="'.implode(' ', $cssClasses).'">' . $label . $content . '</li>';
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