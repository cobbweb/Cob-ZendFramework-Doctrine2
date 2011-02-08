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


$env='development';
foreach($argv as $key => $arg){
    if($arg == '--env'){
        $env = $argv[$key+1];
    }
}

putenv("APPLICATION_ENV=$env");


// Assume we are in my-app/lib/Cob/bin where my-app is our desired SRC_PATH
defined('SRC_PATH')
    || define('SRC_PATH', realpath(dirname(__FILE__) . '/../../../'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', SRC_PATH . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    SRC_PATH . '/lib',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Cob/lib/Cob/Application/Application.php';

// Creating application
$application = new \Cob\Application\Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.yml'
);

// Bootstrapping resources
$bootstrap = $application->bootstrap()->getBootstrap();