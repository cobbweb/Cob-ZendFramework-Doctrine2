<?php

/**
 * My Promotions eCommerce Platform
 * Copyright © 2010-2011 Promocon Group Pty. Ltd.
 */
namespace Cob\Doctrine\Fixture;

/**
 * 
 * Interface FixtureRunner
 *
 * @author Andrew Cobby <andrew@promocongroup.com.au>
 */
interface FixtureRunner
{

    public function run();
    
    public function executeFixture(Fixture $fixture);
    
    public function executeFixtures(array $fixtures);

}
