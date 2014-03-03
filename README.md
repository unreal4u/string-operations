[![Latest Stable Version](https://poser.pugx.org/unreal4u/string-operations/v/stable.png)](https://packagist.org/packages/unreal4u/string-operations)
[![Build Status](https://travis-ci.org/unreal4u/string-operations.png?branch=master)](https://travis-ci.org/unreal4u/string-operations)
[![License](https://poser.pugx.org/unreal4u/string-operations/license.png)](https://packagist.org/packages/unreal4u/string-operations)

stringOperations.php
======

Credits
--------

This class is made by unreal4u (Camilo Sperberg). [http://unreal4u.com/](unreal4u.com)

About this class
--------

* Originally conceived to be a receiver of all loose string-related functions that I've made throughout the years
* Later also a playground to learn about multibyte capabilities of strings

Detailed description
---------

This package is a collection of functions related to string manipulation.

Basic usage
----------

<pre>include('src/unreal4u/stringOperations.php');
$stringOperations = new unreal4u\stringOperations();
$result = $stringOperations->truncate('this is a bigger text', 15);
</pre>

* Congratulations! Result will have a truncated string, compatible with UTF-8 characters:
 <pre>
 this is a bigger...
 </pre>
* Please see examples and PHPUnit tests for more options and advanced usage

Composer
----------

This class has support for Composer install. Just add the following section to your composer.json with:

<pre>
{
    "require": {
        "unreal4u/string-operations": "@stable"
    }
}
</pre>

Now you can instantiate a new stringOperations class by executing:

<pre>
require('vendor/autoload.php');

$stringOperations = new unreal4u\stringOperations();
</pre>

Pending
---------
* Search for more loose functions spattered around my codebase
* Improve the usage of the internal used charset
* Consistent usage of the class and it subcomponents (UTF-8 in createSlug)
* Multiple arguments for separator in truncate function

Version History
----------

* 0.1:
    * Created class
* 0.1.1:
    * Documentation fixes
* 0.2.0:
    * Added decomposeCompleteEmail() function and unit tests for it
* 0.2.2:
    * Documentation update
* 0.3.0:
    * Composer compatibility
    * Documentation and examples update
* 0.3.1:
    * Renamed repo and moved PHPUnit as dependency
* 0.3.2:
    * Travis-CI support
    * Gitattributes
    * Documentation fixes
* 1.0.0:
    * Truncate function improvements and BC breaks
        * This function can now search backwards in the string
        * This function now supports multiple separators
    * Excluded some things from a package

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
