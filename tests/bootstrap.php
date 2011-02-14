<?php

/*
 * Copyright (C) 2011 by Andrew Cobby <cobby@cobbweb.me>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once 'Doctrine/Common/ClassLoader.php';

set_include_path(implode(PATH_SEPARATOR, array(
    realpath('../lib'),
    get_include_path(),
)));

$loaders = array(
    array('ns' => 'Cob\Tests', 'path' => realpath('./')),
    array('ns' => 'Cob', 'path' => null),
    array('ns' => 'Doctrine\ORM', 'path' => null),
    array('ns' => 'Doctrine\Common', 'path' => null),
    array('ns' => 'Doctrine\DBAL', 'path' => null),
    array('ns' => 'Symfony', 'path' => null)
);

foreach($loaders as $loader){
    $classLoader = new Doctrine\Common\ClassLoader($loader['ns'], $loader['path']);
    $classLoader->register();
}
