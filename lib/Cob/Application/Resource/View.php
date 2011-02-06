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


namespace Cob\Application\Resource;

use Cob\View\View as BaseView,
    Cob\Controller\Action\HelperBroker;

/**
 * Description of View
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class View extends \Zend_Application_Resource_View
{

    /**
     * Register Cob view helpers and use our own View object
     * 
     * @return BaseView
     */
    public function init()
    {
        $options = $this->getOptions();
        $this->_view = new BaseView($options);

        if(isset($options['doctype'])){
            $this->_view->doctype()->setDoctype(strtoupper($options['doctype']));
        }

        $this->_view->addHelperPath('Cob/View/Helper', 'Cob\\View\\Helper\\');

        $view = parent::init();

        HelperBroker::getExistingHelper('ViewRenderer')
                ->setViewBasePathSpec(':moduleDir/..');

        return $view;
    }

}