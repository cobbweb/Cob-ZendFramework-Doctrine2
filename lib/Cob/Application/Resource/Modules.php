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

use Cob\Loader\ModuleAutoloader;

/**
 * Setup module-based autoloaders and bootstrapping
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Modules extends \Zend_Application_Resource_Modules
{

    /**
     * @var null|ModuleAutoloader
     */
    protected $_autoloader = null;

    /**
     * Initialize the application's modules
     *
     * Set up the autoloader for all modules.  Call all module bootstraps that
     * exist.
     *
     * @return \ArrayObject
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        $this->getAutoloader()->register();

        foreach($front->getControllerDirectory() as $name => $path){
            $this->setupModule($name, dirname($path));
        }

        return $this->_bootstraps;
    }

    /**
     * Set up the module with the given name and path
     * 
     * @param string $name
     * @param string $path
     */
    public function setupModule($name, $path)
    {
        // Do not bootstrap the module again
        if(isset($this->_bootstraps[$name])){
            return;
        }
        
        $this->_addToAutoloader($name, $path);
        $this->_bootstrapModule($name, $path);
    }

    /**
     * Get the module autoloader
     * 
     * @return ModuleAutoloader
     */
    public function getAutoloader()
    {
        if(null === $this->_autoloader){
            $this->setAutoloader(new ModuleAutoloader($this->getBootstrap()->getAppNamespace()));
        }

        return $this->_autoloader;
    }

    /**
     * Set the module autoloader
     *
     * @param  ModuleAutoloader $autoloader
     * @return Modules *Fluent interface*
     */
    public function setAutoloader(ModuleAutoloader $autoloader)
    {
        $this->_autoloader = $autoloader;

        return $this;
    }

    /**
     * Add the given module to the module autoloader
     *
     * @param string $name
     * @param string $path
     */
    protected function _addToAutoloader($name, $path)
    {
        $this->getAutoloader()->addModule($name, $path);
    }

    /**
     * Call the module bootstrap, if it exists.
     * 
     * @param string $name
     */
    protected function _bootstrapModule($name, $path)
    {
        $bootstrapFile = sprintf(
                '%s' . DIRECTORY_SEPARATOR . 'Bootstrap.php', $path
        );
	
        if(!file_exists($bootstrapFile)){
            return;
        }

        $bootstrapClass = $this->_getModuleBootstrapClass($name);
        $bootstrap = new $bootstrapClass($this->getBootstrap());
        
        if(!$bootstrap instanceof \Zend_Application_Bootstrap_Bootstrapper){
            throw new \LogicException('Module bootstraps must implement Zend_Application_Bootstrap_Bootstrapper');
        }

        $bootstrap->bootstrap();
        $this->_bootstraps[$name] = $bootstrap;
    }

    /**
     * Get the class of the given module's bootstrap
     *
     * @param  string $name
     * @return string
     */
    protected function _getModuleBootstrapClass($name)
    {
        $bootstrap = sprintf('%s\\Bootstrap', $this->_formatModuleName($name));
        
        if($appNamespace = $this->getBootstrap()->getAppNamespace()){
            $bootstrap = $appNamespace . '\\' . $bootstrap;
        }
        
        return $bootstrap;
    }

}