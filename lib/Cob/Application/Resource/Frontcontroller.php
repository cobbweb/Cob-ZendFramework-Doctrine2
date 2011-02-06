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


namespace Cob\Application\Resource;

/**
 * Custom FrontController resource to inject out own distpacher object
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Frontcontroller extends \Zend_Application_Resource_Frontcontroller
{
    
    /**
     * Get the Front Controller with a custom dispatcher class
     *
     * @return Zend_Fontroller_Front
     */
    public function getFrontController()
    {
        if(null === $this->_front){
            $this->_front = \Zend_Controller_Front::getInstance();

            $options = $this->getOptions();

            if(array_key_exists('dispatcherClass', $options)){
                $dispatcher = new $options['dispatcherClass']();
                $this->_front->setDispatcher($dispatcher);
            }
            $this->_front->setParam('prefixDefaultModule', true)
                         ->setModuleControllerDirectoryName('src/Controller')
                         ->setParam('appNamespace', $this->getBootstrap()->getAppNamespace());
        }
        
        return $this->_front;
    }
    
}