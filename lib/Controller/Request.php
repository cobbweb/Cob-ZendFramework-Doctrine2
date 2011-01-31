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

namespace Cob\Controller;

/**
 * Custom request class with a few helper methods
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class Request extends \Zend_Controller_Request_Http
{

    /**
     * Returns true if the given MIME type is in the Accept header
     *
     * @param string $contentType
     * @return bool
     */
    public function accepts($contentType)
    {
        return strstr($this->getHeader('Accept'), $contentType);
    }

    /**
     * Returns true is the Accept header is JSON
     *
     * @return bool
     */
    public function acceptsJson()
    {
        return $this->accepts('application/json') ||  $this->serviceFormatIs('json');
    }

    /**
     * Returns true is the Accept header is XML
     *
     * @return bool
     */
    public function acceptsXml()
    {
        return ($this->accepts('application/xml') && !$this->acceptsHtml()) || $this->serviceFormatIs('xml');
    }

    /**
     * Returns true is the Accept header is HTML
     *
     * @return bool
     */
    public function acceptsHtml()
    {
        return $this->accepts('text/html');
    }

    /**
     * Returns true if the provided MIME-type is in the Content-type header
     *
	 * @var $type MIME-type
     * @return bool
     */
    public function contentTypeIs($type)
    {
        return strpos($this->getHeader('Content-Type'), $type) !== false;
    }
    
}