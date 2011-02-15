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

namespace Cob\Tests\ORM;

/**
 * Entity Utility tests
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class EntityUtilTest extends OrmTestCase
{
    
    private $eu;
    
    public function setUp()
    {
	parent::setUp();
	
	$this->eu = new \Cob\ORM\EntityUtil($this->em);
    }

    
    public function testCanCreateEntities()
    {
	$data = array(
	    'name'  => 'Basketball',
	    'price' => 58.99
	);
	
	$product = $this->eu->createEntity(new Entity\Product(), $data);
	
	$this->assertEquals($data['name'], $product->getName());
	$this->assertEquals($data['price'], $product->getPrice());
    }
    
    /**
     * @expectedException Cob\ORM\InvalidPropertyException
     */
    public function testCannotAddInvalidProperty()
    {
	$data = array(
	    'name'     => 'Basketball',
	    'category' => 'Balls'
	);
	
	$product = $this->eu->createEntity(new Entity\Product(), $data);
    }

}
