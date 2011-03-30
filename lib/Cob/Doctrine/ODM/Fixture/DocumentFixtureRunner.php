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

namespace Cob\Doctrine\ODM\Fixture;

use Doctrine\ODM\MongoDB\DocumentManager,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Output\Output,
    Cob\Doctrine\Fixture\Fixture,
    Cob\Doctrine\Fixture\Loader;

/**
 * Responsible for loading and fixtures; and resolving dependencies
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class DocumentFixtureRunner implements \Cob\Doctrine\Fixture\FixtureRunner
{
    
    /**
     * Loaded fixtures
     *
     * @var array
     */
    protected $_fixtures = array();

    /**
     * Executed fixtures
     *
     * @var array
     */
    protected $_executedFixtures = array();

    /**
     * Instance of the EntityManager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $dm;

    /**
     * Instance of the console output writer
     *
     * @var \Symfony\Components\Console\Output\ConsoleOutput
     */
    protected $_writer;
    
    protected $_loaders;

    /**
     * Class constructor
     *
     * @param EntityManager $entityManager
     * @param ConsoleOutput $writer
     */
    public function __construct(DocumentManager $dm, OutputInterface $writer)
    {
        $this->dm = $dm;
        $this->_writer = $writer;
    }
    
    public function addLoader(Loader $loader)
    {
        $this->_loaders[] = $loader;
    }
    
    public function addLoaders(array $loaders)
    {
        array_map(array($this, 'addLoader'), $loaders);
    }

    /**
     * Enable/disable quite mode
     *
     * @param bool $flag
     */
    public function quietMode($flag)
    {
        if($flag){
            $this->_writer->setVerbosity(Output::VERBOSITY_QUIET);
        }else{
            $this->_writer->setVerbosity(Output::VERBOSITY_NORMAL);
        }
    }
    
    public function run()
    {
        foreach($this->_loaders as $loader){
            $this->_runLoader($loader);
        }
        
        $this->dm->flush();
    }
    
    protected function _runLoader(Loader $loader)
    {
        $this->executeFixtures($loader->getFixtures());
    }

    /**
     * Execute an array of fixture classes
     *
     * @param array $fixtureClasses
     */
    public function executeFixtures(array $fixtures)
    {
        foreach($fixtures as $fixture){
            $this->executeFixture($fixture);
        }
    }

    /**
     * Execute a fixture
     *
     * @param Fixture $fixture
     * @return void
     */
    public function executeFixture(Fixture $fixture)
    {
        $fixtureClass = get_class($fixture);
        
        if($this->_isExecuted($fixture)){
            return; // already executed
        }
        
        $this->_resolveDependencies($fixture);
        
        $this->_writer->writeln("Running fixture {$fixtureClass}");
        
        $fixture->setdocumentManager($this->dm);
        $fixture->run();
        
        $this->_executedFixtures[] = $fixtureClass;
        
        $this->_writer->writeln("{$fixtureClass} loaded");
        $this->_writer->writeln('');
    }

    /**
     * Resolve dependencies for a fixture
     *
     * @param Fixture $fixture
     */
    protected function _resolveDependencies(Fixture $fixture)
    {
        $dependencies = $fixture->getDependencies();

        if($dependencies){
            if(is_string($dependencies)){
                $dependentClass = new $dependencies;
                $this->executeFixture($dependentClass);
            }else if(is_array($dependencies)){
                foreach($dependencies as $dependency){
                    $fixtureClass = new $dependency;
                    $this->executeFixture($fixtureClass);
                }
            }
        }
    }

    /**
     * Returns true if the fixture has already been executed
     *
     * @param Fixture $class
     * @return bool
     */
    protected function _isExecuted(Fixture $fixture)
    {
        return in_array(get_class($fixture), $this->_executedFixtures);
    }
    
}