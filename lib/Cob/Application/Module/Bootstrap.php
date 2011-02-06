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

namespace Cob\Application\Module;

/**
 * Custom module bootstrapper to override resource autoloader
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
abstract class Bootstrap extends \Zend_Application_Module_Bootstrap
{
    
     protected $viewHelperPath = "null";
    
    /**
     * Constructor
     *
     * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct($application)
    {
        $this->setApplication($application);

        $key = strtolower($this->getModuleName(false));
        if ($application->hasOption($key)) {
            // Don't run via setOptions() to prevent duplicate initialization
            $this->setOptions($application->getOption($key));
        }

        // Use same plugin loader as parent bootstrap
        if ($application instanceof \Zend_Application_Bootstrap_ResourceBootstrapper) {
            $this->setPluginLoader($application->getPluginLoader());
        }

        // ZF-6545: ensure front controller resource is loaded
        if (!$this->hasPluginResource('FrontController')) {
            $this->registerPluginResource('FrontController');
        }

        // ZF-6545: prevent recursive registration of modules
        if ($this->hasPluginResource('modules')) {
            $this->unregisterPluginResource('modules');
        }
    }

    /**
     * Initialize the plugin paths for this module
     */
    public function _initPluginPaths()
    {
        $app = $this->getApplication();
        
        $app->bootstrap('View');
        $app->bootstrap('FrontController');
        
        $front = $app->getResource('FrontController');
        $view  = $app->getResource('View');
        
        $module = $this->getModuleName(false);
        $namespace = $app->getAppNamespace() . '\\' . $module . '\View\Helper\\';
        $helperPath = $front->getModuleDirectory(strtolower($module)) . '/View/Helper';
        
        $view->addHelperPath($helperPath, $namespace);
    }
    
    public function _initAutoLoadRoutes()
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        
        $routesFile = $front->getModuleDirectory(strtolower($this->getModuleName(false))) . '/../configs/routes.yml';
        
        if(file_exists($routesFile)){
            $config = new \Zend_Config_Yaml($routesFile);
            $router = $front->getRouter();
            $router->addConfig($config, 'routes');
        }
    }

    /**
     * Retrieve module name
     *
     * @return string
     */
    public function getModuleName($withNamespace=true)
    {
        $class = get_class($this);

        if(!$withNamespace){
            // remove application namespace
            $class = str_replace($this->getApplication()->getAppNamespace() . "\\", "", $class);
        }

        if (preg_match('/^([a-z][a-z0-9]*)\\\/i', $class, $matches)) {
            $prefix = $matches[1];
        } else {
            $prefix = $class;
        }
        $this->_moduleName = $prefix;
        
        return $this->_moduleName;
    }
    
}