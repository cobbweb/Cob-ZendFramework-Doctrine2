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

use Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\MongoDB\Connection,
    Doctrine\ODM\MongoDB\Configuration,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
    Doctrine\Common\EventManager,
    Promocon\Doctrine\ODM\Sluggable\SlugSubscriber;

/**
 * Creates a MongoDB connection and DocumentManager instance
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Mongo extends \Zend_Application_Resource_ResourceAbstract
{

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function init()
    {
        \Doctrine\ODM\MongoDB\Mapping\Types\Type::addType('arraycollection', 'Promocon\Doctrine\ODM\Mapping\Types\ArrayCollectionType');
        
        $options = $this->getOptions() + array(
            'defaultDB'          => 'mypromotions',
            'proxyDir'          => APPLICATION_PATH . '/domain/Proxies',
            'proxyNamespace'    => 'Application\Proxies',
            'hydratorDir'       => APPLICATION_PATH . '/domain/Hydrators',
            'hydratorNamespace' => 'Application\Hydrators'
        );

        $config = new Configuration();
        $config->setProxyDir($options['proxyDir']);
        $config->setProxyNamespace($options['proxyNamespace']);
        $config->setHydratorDir($options['hydratorDir']);
        $config->setHydratorNamespace($options['hydratorNamespace']);
        $config->setDefaultDB($options['defaultDB']);
        
        $reader = new AnnotationReader();
        $reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\\');
        $config->setMetadataDriverImpl(new AnnotationDriver($reader, $this->getDocumentPaths()));

        $evm = new EventManager();
        $evm->addEventSubscriber(new SlugSubscriber());

        return DocumentManager::create(new Connection(), $config, $evm);
    }
    
    public function getDocumentPaths()
    {
        $paths = array();
        foreach(new \DirectoryIterator(APPLICATION_PATH . '/modules') as $module){
            $path = $module->getPathname() . '/src/Domain/Document';
            
            if((!$module->isDir() || $module->isDot()) || !is_dir($path)){
                continue;
            }
            
            $paths[] = $path;
        }
	
        if(!isset($paths)){
            throw new \LogicException("No documents found");
        }
        
        return $paths;
    }
    
}