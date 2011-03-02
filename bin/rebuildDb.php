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

$rebuild = new Rebuild(new Symfony\Component\Console\Output\ConsoleOutput());
$rebuild->run();

/**
 * Description of rebuildDb
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Rebuild
{
    
    private $writer;
    
    public function __construct(Symfony\Component\Console\Output\OutputInterface $writer)
    {
        $this->writer = $writer;
        chdir(__DIR__);
        $_SERVER['SHELL'] = true;
    }

    public function deleteFolder($path)
    {
        $path = realpath($path);
        $this->writer->writeln("Deleting $path");

        $this->deleteRecursive($path);
        $this->writer->writeln("...deleted!");
        $this->writer->writeln('');
        
    }

    public function deleteRecursive($path)
    {
        if(is_dir($path)){
            foreach(new DirectoryIterator($path) as $item){
                if(!$item->isDot()){
                    $this->deleteRecursive($item->getPathname());
                }
            }
            $this->deleteDirectory($path);
        }else if(is_file($path)){
            $this->deleteFile($path);
        }
    }

    public function deleteDirectory($path){
        if(!is_dir($path)){
            return false;
        }
        
        $this->writer->writeln("Deleting folder: $path");
        rmdir($path);
    }

    public function deleteFile($path)
    {
        if(!is_file($path)){
            return false;
        }
        $this->writer->writeln("Deleting file: $path");
        unlink($path);
    }

    public function createDirectory($path, $mode=0777)
    {
        if(is_dir($path)){
            throw new \InvalidArgumentException("Provided path ($path) exists!");
        }
        
        mkdir($path, $mode);
        chmod($path, 0777);
    }

    public function emptyFolder($path)
    {
        $this->deleteFolder($path);
        $this->createDirectory($path);
    }

    public function generateProxies()
    {
        $this->writer->writeln('Generating proxies');
        $this->doctrineCommand("orm:generate-proxies");
        $this->writer->writeln('');
    }

    public function dropDatabase()
    {
        $this->writer->writeln('Dropping database');
        $this->doctrineCommand('orm:schema-tool:drop --force');
        $this->writer->writeln('');
    }

    public function createDatabase()
    {
        $this->writer->writeln('Creating database');
        $this->doctrineCommand('orm:schema-tool:create');
        $this->writer->writeln('');
    }

    public function loadFixtures()
    {
        $this->execute('php -f ./loadFixtures.php');
    }

    public function doctrineCommand($command)
    {
        $this->execute("php -f ./doctrine.php $command");
    }

    public function execute($command)
    {
        system($command);
    }

    public function deleteProxies()
    {
        $this->writer->writeln("Deleting proxies");
        $this->emptyFolder(APPLICATION_PATH . '/domain/Proxies');
    }

    public function run()
    {
        $this->writer->writeln("Rebuilding proxies and database");
        
        $this->dropDatabase();
        $this->deleteProxies();
        $this->generateProxies();
        $this->createDatabase();
        $this->loadFixtures();
    }
    
}