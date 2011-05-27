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

namespace Cob\Stdlib;

/**
 * A collection of string functions
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class String
{
    
	static private $siteKey = "You shou1d 0v3r1d3 th1s;";
    
	/**
	 * The current string
	 */
    private $string;
    
	/**
	 * @param $string string String to work on
	 */
    public function __construct($string)
    {
        $this->string = (string) $string;
    }

    static public function setSiteKey($siteKey)
    {
        self::$siteKey = $siteKey;
    }

	/** 
	 * Factory method for inline usage
	 */
    static public function create($string)
    {
        return new self($string);
    }
    
	/**
	 * Calculate the SHA1 hash of the string
	 */
    public function password()
    {
        return hash('sha512', self::$siteKey.$this->string);
    }
    
	/**
	 * Convert the string to a URL slug
	 */
    public function slugize()
    {
        $string = $this->string;
        $string = trim($string); // trim whitespace
        $string = preg_replace('/[^a-z0-9-]/i', ' ', $string); // replace invalid characters with spaces
        $string = str_replace(' ', '-', $string); // replaces spaces with dashes
        $string = preg_replace('/-+/', '-', $string); // replace multi-dashes with one dash
        $string = strtolower($string); // convert to lowercase
        $string = trim($string, '-');
        return $string;
    }
    
	/**
	 * PHP's missing str_replace_all function
	 */
    public function multiReplace(array $search, array $replace)
    {
        // convert to numeric indexes
        $search = array_values($search);
        $replace = array_values($replace);
        $subject = $this->string;

        if(count($search) > count($replace)){
            throw new \InvalidArgumentException('Not enough replacements provided');
        }

        foreach($search as $key => $value){
            $subject = str_replace($value, $replace[$key], $subject);
        }
        
        return $subject;
    }

    public function __toString()
    {
        return $this->string;
    }
    
	/**
	 * Factory method to start with a random string
	 */
    static public function createFromRandom($length = 8)
    {
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($characters);
		$rand = '';

        do {
            $rand .= $characters[rand(0, $size - 1)];
        }while(strlen($rand) < 32);

        $rand .= time();
        $rand = str_shuffle($rand);

        return new self(substr($rand, 0, $length));
    }
    
}