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

namespace Cob\Controller;

/**
 * Custom MVC dispatcher that uses namespaces
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Dispatcher extends \Zend_Controller_Dispatcher_Standard
{

    /**
     * Default controller
     * @var string
     */
    protected $_defaultController = 'Index';
    /**
     * Default module
     * @var string
     */
    protected $_defaultModule = 'core';

    /**
     * Determine if a given module is valid
     *
     * @param  string $module
     * @return bool
     */
    public function isValidModule($module)
    {
        if(!is_string($module)){
            return false;
        }

        $module = $this->formatModuleName($module);
        $controllerDir = $this->getControllerDirectory();
        
        foreach(array_keys($controllerDir) as $moduleName){
            if($module == $this->formatModuleName($moduleName)){
                return true;
            }
        }

        return false;
    }

    /**
     * Format action class name
     *
     * Unlike in Zend Framework, controllers always have Controller in the
     * namespace.
     *
     * @param  string  $moduleName Name of the current module
     * @param  string  $className Name of the action class
     * @return string Formatted class name
     */
    public function formatClassName($moduleName, $className)
    {
        $className = $this->formatModuleName($moduleName) . '\\Controller\\' . $className;
        
        if($this->getParam('appNamespace')){
            $className = $this->getParam('appNamespace') . '\\' . $className;
        }
        
        return $className;
    }

    public function classToFilename($class) {
        $class = parent::classToFilename($class);
        $class = str_replace('\\', '/', $class);
        return $class;
    }


    /**
     * Formats a string from a URI into a PHP-friendly name.
     *
     * By default, replaces words separated by the word separator character(s)
     * with camelCaps. If $isAction is false, it also preserves replaces words
     * separated by the path separation character with a backslash, making
     * the following word Title cased. All non-alphanumeric characters are
     * removed.
     *
     * @param  string  $unformatted
     * @param  boolean $isAction Defaults to false
     * @return string
     */
    protected function _formatName($unformatted, $isAction = false)
    {
        $implodedSegments = parent::_formatName($unformatted, $isAction);

        return str_replace('_', '\\', $implodedSegments);
    }

}