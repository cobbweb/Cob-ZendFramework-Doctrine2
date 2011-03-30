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

use Doctrine\ORM\Configuration,
    Doctrine\Common\Cache\ApcCache,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\EventManager,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * Creates a Doctrine connection and EntityManager instance
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    
    /**
     * Connection options.
     * 
     * @var array
     */
    protected $_params = array();
    
    /**
     * Proxy path.
     * 
     * @var string
     */
    protected $_proxyPath;
    
    /**
     * Proxy namespace.
     * 
     * @var string
     */
    protected $_proxyNamespace = 'Application\Proxies';
    
    /**
     * Setup caches and config and then create an EntityManager
     */
    public function init()
    {
        // Create the EntityManager
        $config = new Configuration();
        
        // check APC is available (for CLI compatibility)
        if(function_exists('apc_fetch')){
	        $cache = new ApcCache();
	        $config->setMetadataCacheImpl($cache);
	        $config->setQueryCacheImpl($cache);
        }
        
        $evt = new EventManager();
        
        $driver = AnnotationDriver::create($this->getEntitiesPaths());
        
        $config->setMetadataDriverImpl($driver);
        
        $config->setProxyDir($this->getProxyPath());
        $config->setProxyNamespace($this->getProxyNamespace());
        $config->setAutoGenerateProxyClasses(true);
        
        $params = $this->getParams();
        
        $em = EntityManager::create($params, $config, $evt);
        
        return $em;
    }
    
    /**
     * Set connection options.
     * 
     * @param array $params Connection options
     * @return Doctrine
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }
    
    /**
     * Get connection options.
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    public function getEntitiesPaths()
    {
        foreach(new \DirectoryIterator(APPLICATION_PATH . '/modules') as $module){
            $path = $module->getPathname() . '/src/Domain/Entity';
            
            if((!$module->isDir() || $module->isDot()) || !is_dir($path)){
                continue;
            }
            
            $paths[] = $path;
        }
	
	if(!isset($paths)){
	    throw new \LogicException("No entities found");
	}
        
        return $paths;
    }
    
    /**
     * Set the proxy directory path.
     * 
     * @param string $proxyPath Proxy Directory
     * @return Doctrine
     */
    public function setProxyPath($proxyPath)
    {
        $this->_proxyPath = $proxyPath;
        return $this;
    }
    
    /**
     * Get proxy directory path.
     * 
     * @return string
     */
    public function getProxyPath()
    {
        return $this->_proxyPath;
    }
    
    /**
     * Set proxy namespace.
     * 
     * @param string $namespace Namespace
     * @return Doctrine
     */
    public function setProxyNamespace($namespace)
    {
        $this->_proxyNamespace = $namespace;
        return $this;
    }
    
    /**
     * Get proxy namespace.
     * 
     * @return string
     */
    public function getProxyNamespace()
    {
        return $this->_proxyNamespace;
    }

    /**
     * Get a specific option based on key
     *
     * @param string $key
     * @return mixed
     */
    public function getOption($key)
    {
        return isset($this->_options[$key]);
    }
    
}