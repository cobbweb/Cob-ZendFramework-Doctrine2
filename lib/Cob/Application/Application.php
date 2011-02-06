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

namespace Cob\Application;

use Doctrine\Common\ClassLoader;

require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';

/**
 * Custom Application that setups up our autoloaders
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Application extends \Zend_Application
{
    
    public function __construct($environment, $options = null)
    {
        $this->_environment = (string) $environment;
        
        require_once 'Doctrine/Common/ClassLoader.php';
        
        $loader = new ClassLoader('Zend');
        $loader->setNamespaceSeparator('_');
        $loader->register();
        
        $loader = new ClassLoader('Doctrine\ORM');
        $loader->register();
        
        $loader = new ClassLoader('Doctrine\DBAL');
        $loader->register();
        
        $loader = new ClassLoader('Doctrine\Common');
        $loader->register();
        
        $loader = new ClassLoader('Symfony');
        $loader->register();
        
        if (null !== $options) {
            if (is_string($options)) {
                $options = $this->_loadConfig($options);
            } elseif ($options instanceof Zend_Config) {
                $options = $options->toArray();
            } elseif (!is_array($options)) {
                throw new Zend_Application_Exception('Invalid options provided; must be location of config file, a config object, or an array');
            }

            $this->setOptions($options);
        }
    }

    
}