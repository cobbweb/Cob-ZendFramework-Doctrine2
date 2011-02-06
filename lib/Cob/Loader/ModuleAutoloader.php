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


namespace Cob\Loader;

/**
 * Autoloader for module resources
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class ModuleAutoloader implements \Zend_Loader_Autoloader_Interface
{

    private $appNamespace;

    /**
     * @var array
     */
    protected $_modules = array();

    public function __construct($appNamespace = "")
    {
        $this->appNamespace = trim((string) $appNamespace, '\\');
    }

    /**
     * Register the autoloader with the SPL autoload stack
     */
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Add a module to the autoloader
     * 
     * @param  string $module
     * @param  string $path
     * @return ModuleAutoloader *Fluent interface*
     *
     */
    public function addModule($module, $path)
    {
        $this->_modules[$module] = rtrim($path, DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * Load the given class if it matches any of the modules
     * 
     * @param string $class
     */
    public function autoload($class)
    {
        $baseNamespace = $this->appNamespace . '\\';

        foreach($this->_modules as $module => $path){
            $namespace = trim($baseNamespace . ucfirst($module), '\\') . '\\';

            if(strpos($class, $namespace) === 0){
                $class = str_replace($namespace, '', $class);
                require $path . DIRECTORY_SEPARATOR
                        . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

                return true;
            }
        }

        return false;
    }

}