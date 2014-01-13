# Cob library

A collection of components for [Zend Framework](http://www.zendframework.com) and [Doctrine 2](http://www.doctrine-project.org).
Mostly my own work with some inspiration from the [Epixa library](https://github.com/epixa/Epixa)

## Module structure
Here is what an example module might look like:

  /application
    /modules
      /blog
        /configs
          navigation.xml
          routes.yml
        /src (Namespace is Application\Blog)
          /Bootstrap.php
          /Controller
            IndexController.php
              PostController.php
          /Domain
            /Entity
              Post.php
              PostVersion.php
              PostSlug.php
            /Fixture
              PostFixture.php
            /Repository
              PostRepository.php
            /Service
              PostServiceInterface.php
              PostService.php
          /View
            /Helper
        /views
          /index
            index.phtml
            post.phtml
            category.phtml
                
    


## MIT License 
These components are released under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

> Copyright (C) 2011 by Andrew Cobby
