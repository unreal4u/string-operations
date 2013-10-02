stringOperations.class.php
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

<pre>include('stringOperations.class.php');
$stringOperations = new stringOperations();
$result = $stringOperations->truncate('this is a bigger text', 15);
</pre>

* Congratulations! Result will have a truncated string, compatible with UTF-8 characters:
 <pre>
 this is a bigger...
 </pre>
* Please see examples and PHPUnit tests for more options and advanced usage

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

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
