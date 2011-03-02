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
 * Custom Application that sets up our autoloaders
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Application extends \Zend_Application
{
    
    public function __construct($environment, array $config)
    {
        $this->_environment = (string) $environment;
        
        require_once 'Doctrine/Common/ClassLoader.php';
        
        $loader = new ClassLoader('Zend', SRC_PATH . '/lib/ZendFramework/library');
        $loader->setNamespaceSeparator('_');
        $loader->register();
	
        $loader = new ClassLoader('Cob', SRC_PATH . '/lib/Cob/lib');
        $loader->register();
        
        $this->_initConfig($config);
    }

    private function _initConfig(array $config)
    {   
        
        $configFile = "{$config['directory']}/production.{$config['format']}";
        $configuration = $this->_createConfig($configFile, 'production');
        
        if('production' !== $this->getEnvironment()){
            $configFile = "{$config['directory']}/" . $this->getEnvironment() . ".{$config['format']}";
            $configuration->merge($this->_createConfig($configFile, $this->getEnvironment()));
            $configuration->setReadOnly();
        }
        
        $this->setOptions($configuration->toArray());
    }
    
    private function _createConfig($file, $section)
    {
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        switch($suffix){
            case 'xml':
                $config = new \Zend_Config_Xml($file, $section, true);
            break;
        
            case 'ini':
                $config = new \Zend_Config_Ini($file, $section, true);
            break;    
                
            case 'yaml':
            case 'yml':
                $config = new \Zend_Config_Yaml($file, $section, true);
            break;
        
            case 'php':
            case 'inc':
                $config = include $file;
                
                if (!is_array($config)) {
                    throw new Zend_Application_Exception('Invalid configuration file provided; PHP file does not return array value');
                }
                
                $config = new \Zend_Config($config, true);
            break;
        }
        
        return $config;
    }
    
}