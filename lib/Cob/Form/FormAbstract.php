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

namespace Cob\Form;

/**
 * Custom Form that registers Cob decorators and elements. Also function for the 
 * GenericErrors decorator
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class FormAbstract extends \Zend_Form
{
    
    /**
     * Stores generic form errors (not specific to an element)
     *
     * @var array
     */
    protected $_genericErrors = array();
 
    /**
     * Custom form implementation that registers custom decorators
     *
     * @param array|Zend_Config $options 
     */
    public function __construct($options = null)
    {
        if(is_array($options)){
            $this->setOptions($options);
        }elseif($options instanceof Zend_Config){
            $this->setConfig($options);
        }
        
        $this->_setupPrefixPaths();
        
        $this->init();
        
        $this->_setupDefaultElementDecorators();
        $this->_setupDefaultFormDecorators();
    }
    
    /**
     * Register prefix paths
     */
    protected function _setupPrefixPaths()
    {
        $this->addPrefixPath('Cob\Form\Element\\', 'Cob/Form/Element', self::ELEMENT);
        $this->addPrefixPath('Cob\Form\Decorator\\', 'Cob/Form/Decorator', self::DECORATOR);
        $this->addElementPrefixPath('Cob\Form\Decorator\\', 'Cob/Form/Decorator', self::DECORATOR);
    }
    
    /**
     * Setup element decorators
     */
    protected function _setupDefaultElementDecorators()
    {
        if($this->_disableLoadDefaultDecorators){
            return false;
        }
        
        foreach($this->_elements as $element){
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('Label');
            $element->removeDecorator('HtmlTag');

            $element->addDecorator('ListWrapper');
        }
    }
    
    /**
     * Setup form decorators
     */
    protected function _setupDefaultFormDecorators()
    {
        if($this->_disableLoadDefaultDecorators){
            return false;
        }
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'ul', 'class' => 'form')),
            'Form',
            'Fieldset',
            array('GenericErrors', array('placement' => 'prepend'))
        ));
    }
    
    /**
     * Add a generic error
     *
     * @param string $error
     * @return FormAbstract
     */
    public function addGenericError($error)
    {
        $this->_genericErrors[] = $error;
        return $this;
    }

    /**
     * Add multiple generic errors
     *
     * @param array $errors
     * @return FormAbstract
     */
    public function addGenericErrors(array $errors)
    {
        array_walk($errors, array($this, 'addGenericError'));
        return $this;
    }

    /**
     * Reset generic errors to the given error
     *
     * @param string $error
     * @return FormAbstract
     */
    public function setGenericError($error)
    {
        $this->_genericErrors = array($error);
        return $this;
    }

    /**
     * Reset generic errors to the given array of errors
     *
     * @param array $errors
     * @return FormAbstract
     */
    public function setGenericErrors(array $errors)
    {
        $this->_genericErrors = $errors;
        return $this;
    }

    /**
     * Get all generic errors
     *
     * @return array
     */
    public function getGenericErrors()
    {
        return $this->_genericErrors;
    }

    /**
     * Returns true if an generic errors are set
     *
     * @return bool
     */
    public function hasGenericErrors()
    {
        return count($this->_genericErrors) > 0;
    }
    
}