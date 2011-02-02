# Cob library

A collection of components for Zend Framework and Doctrine 2.
Mostly my own work with some inspiration from the [Epixa library](https://github.com/epixa/Epixa)

## MIT License 
These components are released under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

> Copyright (C) 2011 by Andrew Cobby <cobby@cobbweb.me>

> Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

> The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


## Module structure

Here is what an example module might look like:

	/application
		/modules
			/blog
				/configs
					/navigation.xml
					/routes.yml
				/src (Namespace is Application\Blog)
					/Bootstrap.php
					/Controller
						IndexController.php
						PostController.php
					/Domain
						/Entity
							/Post.php
							/PostVersion.php
							/PostSlug.php
						/Fixture
							/PostFixture.php
						/Repository
							/PostRepository.php
						/Service
							/PostServiceInterface.php
							/PostService.php
						/View
							/Helper
				/views
					/index
						/index.phtml
						/post.phtml
						/category.phtml
				
	
