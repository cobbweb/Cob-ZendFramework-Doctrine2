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

require 'bootstrap.php';

$bootstrap->bootstrap('mongo');

$dm = $application->getBootstrap()->getResource('mongo');
$writer = new \Symfony\Component\Console\Output\ConsoleOutput();

$writer->writeln("Loading fixtures...");

$it = new DirectoryIterator(APPLICATION_PATH . '/modules');

while($it->valid()){
    if($it->isDot() || !$it->isDir()){
        $it->next();
        continue;
    }
    
    if(is_dir($it->getPathname() . '/src/Domain/Fixture')){
        $namespace = "Application\\" . ucfirst($it->getFilename()) . "\Domain\Fixture";
        $loaders[] = new \Cob\Doctrine\Fixture\ModuleFixtureLoader($namespace, $it->getPathname() . '/src/Domain/Fixture');
    }
    
    $it->next();
}

$runner = new \Cob\Doctrine\ODM\Fixture\DocumentFixtureRunner($dm, $writer);
$runner->addLoaders($loaders);
$runner->run();
