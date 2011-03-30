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

namespace Cob\Doctrine\Fixture;

/**
 * Fixture loader for Zend Framework modules
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class ModuleFixtureLoader implements Loader
{
    
    /**
     * Base namespace
     *
     * @var string
     */
    protected $_namespace;
    
    /**
     * Path to module folder
     * @var string
     */
    protected $_path;
    
    public function __construct($namespace, $path)
    {
        $this->_namespace = $namespace;
        $this->_path      = $path;
    }
    
    /**
     * Loads the fixtures
     * @return array Array of fixtures
     */
    public function getFixtures()
    {
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->_path));
        $fixtures = array();
        
        while($it->valid()){
            if(!$it->isFile()){
                $it->next();
                continue;
            }
            
            if(preg_match('/[a-z]+Fixture\.php$/i', $it->getFilename())){
                $class = $this->_namespace . '\\' . substr($it->getSubPathname(), 0, -4);
                $class = str_replace('/', '\\', $class);
                $fixtures[] = new $class;
            }
            
            $it->next();
        }
        
        return $fixtures;
    }
    
}