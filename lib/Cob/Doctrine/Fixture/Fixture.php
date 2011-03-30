<?php

/**
 * My Promotions eCommerce Platform
 * Copyright © 2010-2011 Promocon Group Pty. Ltd.
 */
namespace Cob\Doctrine\Fixture;

/**
 * 
 * Interface Fixture
 *
 * @author Andrew Cobby <andrew@promocongroup.com.au>
 */
interface Fixture
{

    public function run();
    
    public function getDependencies();

}
