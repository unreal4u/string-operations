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
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/unreal4u/stringOperations"
        }
    ],
    "require": {
        "unreal4u/stringOperations": "@stable"
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
* Consistent usage of the class and it subcomponents

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

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
